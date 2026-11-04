import { promises as fs } from "fs";
import { models } from "@b/db";

const rootPath = process.cwd();

export async function getProduct(id?: string): Promise<any> {
  if (id) {
    const extension = await models.extension.findOne({
      where: { productId: id },
    });

    if (!extension) {
      throw new Error("Extension not found");
    }

    return extension;
  }

  const fileContent = await fs.readFile(`${rootPath}/package.json`, "utf8");
  const content = JSON.parse(fileContent);
  return {
    id: content.id,
    name: content.name,
    version: content.version,
    description: content.description,
  };
}

export async function getBlockchain(id: string): Promise<any> {
  const blockchain = await models.ecosystemBlockchain.findOne({
    where: { productId: id },
  });

  if (!blockchain) {
    throw new Error("Blockchain not found");
  }

  return blockchain;
}

export async function verifyLicense(
  productId: string,
  _license?: string | null,
  _client?: string | null,
  _timeBasedCheck?: boolean
) {
  return {
    status: true,
    message: `Local activation enabled for ${productId}`,
  };
}

export async function activateLicense(
  productId: string,
  _license?: string | null,
  _client?: string | null
) {
  return {
    status: true,
    message: `Local activation enabled for ${productId}`,
  };
}

export async function checkLatestVersion(_productId: string) {
  return {
    status: false,
    message: "Updates are managed locally.",
    latestVersion: null,
  };
}

export async function checkUpdate(
  _productId: string,
  _currentVersion: string
) {
  return {
    status: false,
    message: "Updates are managed locally.",
    updateAvailable: false,
    latestVersion: _currentVersion,
    update_id: "",
  };
}

export async function downloadUpdate(
  _productId: string,
  _updateId: string,
  _version: string,
  _product: string,
  _type?: string
): Promise<never> {
  throw new Error(
    "Automatic vendor updates are disabled. Deploy updates through your own release process."
  );
}
