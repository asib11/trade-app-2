<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   Utils.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

namespace App\Helpers;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use ReflectionClass;
use ZipArchive;

class Utils
{
    
    public static function makeChildClass(string $abstractClass, string $name, array $parameters = [])
    {
        $r = new ReflectionClass($abstractClass);

        
        $class = (string) str($r->getNamespaceName())->append(str($name)->ucfirst()->prepend('\\')->append(class_basename($abstractClass)));

        return app()->makeWith($class, $parameters);
    }

    
    public static function getDateRange(?string $period): array
    {
        if ($period == 'day') {
            return [Carbon::now()->startOfDay(), Carbon::now()];
        } elseif ($period == 'prev_day') {
            return [Carbon::now()->subDay()->startOfDay(), Carbon::now()->startOfDay()->subSecond()];
        } elseif ($period == 'last24') {
            return [Carbon::now()->subHours(24), Carbon::now()];
        } elseif ($period == 'prev_week') {
            return [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->startOfWeek()->subSecond()];
        } elseif ($period == 'month') {
            return [Carbon::now()->startOfMonth(), Carbon::now()];
        } elseif ($period == 'prev_month') {
            return [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->startOfMonth()->subSecond()];
        } elseif ($period == 'year') {
            return [Carbon::now()->startOfYear(), Carbon::now()];
        } elseif ($period == 'prev_year') {
            return [Carbon::now()->subYear()->startOfYear(), Carbon::now()->startOfYear()->subSecond()];
            
        } else {
            return [Carbon::now()->startOfWeek(), Carbon::now()];
        }
    }

    public static function assert($class, $hash, $cb)
    {
        try {
            return Cache::remember('hash_' . class_basename($class), 300, function () use ($class, $hash) {
                return sha1(preg_replace('#\s+#', '', file_get_contents((new ReflectionClass($class))->getFileName()))) == $hash;
            }) ?: $cb();
        } catch (\Throwable $e) {
            
        }
    }

    
    public static function getConstantNameByValue(string $class, object $instance, $value): string
    {
        $r = new ReflectionClass($class);

        return collect($r->getConstants())
            ->filter(function ($constantValue, $constantName) use ($value) {
                return $value === $constantValue;
            })
            ->keys()
            ->first();
    }

    
    public static function getPathToPhp(): string
    {
        return PHP_BINDIR . DIRECTORY_SEPARATOR . 'php';
    }

    
    public static function getCronJobCommand(): string
    {
        return self::getPathToPhp() . ' -d register_argc_argv=On ' . base_path() . DIRECTORY_SEPARATOR . 'artisan schedule:run';
    }

    public static function generateRandomString(int $numberOfBytes): string
    {
        return bin2hex(random_bytes($numberOfBytes));
    }

    
    public static function bcdechex($decimal): string
    {
        $last = bcmod($decimal, 16);
        $remain = bcdiv(bcsub($decimal, $last), 16);

        if ($remain == 0) {
            return dechex($last);
        } else {
            return self::bcdechex($remain) . dechex($last);
        }
    }

    
    public static function bchexdec($hex): string
    {
        if (strlen($hex) == 1) {
            return ctype_xdigit($hex) ? (string) hexdec($hex) : '0';
        } else {
            $remain = substr($hex, 0, -1);
            $last = substr($hex, -1);
            return bcadd(bcmul(16, self::bchexdec($remain)), ctype_xdigit($last) ? hexdec($last) : 0);
        }
    }

    
    public static function fromUnits($value, int $decimals, int $scale): string
    {
        return bcdiv($value, bcpow(10, $decimals), $scale);
    }

    
    public static function toUnits($value, int $decimals): string
    {
        return bcmul(sprintf("%.{$decimals}f", $value), bcpow(10, $decimals));
    }

    
    public static function objectToArray($object): array
    {
        return json_decode(json_encode($object), TRUE);
    }

    
    public static function renderHtml(string $html): string
    {
        return preg_replace_callback(
            "#{{([^{}]+)}}#is",
            fn($matches) => Blade::render('{{' . $matches[1] . '}}'),
            $html
        );
    }

    public static function platformIsWindows(): bool
    {
        return str(php_uname())->lower()->contains('windows');
    }

    public static function supervisorServiceIsRunning(): bool
    {
        return !self::platformIsWindows()
            ? (Process::run('service supervisor status; service supervisord status'))->seeInOutput('artisan queue:work')
            : FALSE;
    }

    public static function isInstalled(string $id): bool
    {
        $pm = app()->make(PackageManager::class);

        $parts = collect([env($pm->getCodeVariable($id)), request()->getHost(), php_uname()]);

        return env($pm->getHashVariable($id)) == sha1($parts->join('='));
    }

    
    public static function getRemoteFileStream(string $url)
    {
        $client = new Client();

        try {
            $response = $client->get($url, ['stream' => TRUE]);
            return $response->getStatusCode() === 200 ? $response->getBody() : NULL;
        } catch (GuzzleException $e) {
            Log::error(sprintf('Failed to get stream from %s: %s', $url, $e->getMessage()));
        }

        return NULL;
    }

    public static function generateSvgImage(
        string $title,
        int $width = 300,
        int $height = 300,
        string $fillColor = '#000000',
        string $textColor = '#ffffff',
        int $fontSize = NULL
    ): string {
        
        $maxCharsPerLine = 10;

        
        $words = explode(' ', $title);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            if (strlen($currentLine . ' ' . $word) <= $maxCharsPerLine) {
                $currentLine .= ($currentLine === '' ? '' : ' ') . $word;
            } else {
                $lines[] = $currentLine;
                $currentLine = $word;
            }
        }

        
        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        
        $fontSize = $fontSize ?: $height / 10;
        $lineHeight = $fontSize * 1.2;

        
        $startY = ($height / 2) - (count($lines) * $lineHeight / 2) + ($lineHeight / 2);

        
        $svgContent = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}">
    <rect width="100%" height="100%" fill="{$fillColor}"/>
SVG;

        
        foreach ($lines as $index => $line) {
            $yPosition = $startY + ($index * $lineHeight);
            $escapedLine = htmlspecialchars($line, ENT_QUOTES, 'UTF-8');
            $svgContent .= <<<TEXT
    <text x="50%" y="{$yPosition}" fill="{$textColor}" font-family="Arial, sans-serif" font-size="{$fontSize}" text-anchor="middle" dominant-baseline="middle">{$escapedLine}</text>
TEXT;
        }

        
        $svgContent .= "\n</svg>";

        
        $dataUri = 'data:image/svg+xml;base64,' . base64_encode($svgContent);

        return $dataUri;
    }

    public static function readFileChunk(string $path, int $bytes): string
    {
        
        if (!file_exists($path)) {
            throw new Exception(sprintf('File does not exist: %s', $path));
        }

        
        $handle = fopen($path, 'rb');
        if (!$handle) {
            throw new Exception(sprintf('Unable to open file: %s', $path));
        }

        
        $fileSize = filesize($path);

        if ($fileSize === 0) {
            return '';
        }

        
        if ($fileSize < $bytes) {
            $bytes = $fileSize;
        }

        
        fseek($handle, -$bytes, SEEK_END);

        
        $data = fread($handle, $bytes);

        
        fclose($handle);

        
        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');

        return $data;
    }

    public static function unzip(string $pathToFile, bool $deleteZip = true): void
    {
        
        $zip = new ZipArchive();

        if ($zip->open($pathToFile) === TRUE) {
            $zip->extractTo(base_path());
            $zip->close();
        }

        
        if ($deleteZip) {
            unlink($pathToFile);
        }
    }

    public static function logHttpRequest(Request $request): void
    {
        info(sprintf(
            'Making %s request to %s: %s %s',
            $request->method(),
            $request->url(),
            json_encode($request->headers(), JSON_PRETTY_PRINT),
            json_encode($request->data(), JSON_PRETTY_PRINT)
        ));
    }

    public static function lessThan(string $a, string $b, int $decimals = 2): bool
    {
        return bccomp($a, $b, $decimals) === -1;
    }

    public static function greaterThan(string $a, string $b, int $decimals = 2): bool
    {
        return bccomp($a, $b, $decimals) === 1;
    }

    public static function isZero(string $number): bool
    {
        return (float) $number === 0.0;
    }
}
