FROM node:20-bookworm-slim AS build

WORKDIR /app

ARG BACKEND_INTERNAL_HOST=localhost
ENV BACKEND_INTERNAL_HOST=${BACKEND_INTERNAL_HOST}
ARG NEXT_PUBLIC_LANGUAGES=en
ENV NEXT_PUBLIC_LANGUAGES=${NEXT_PUBLIC_LANGUAGES}
ARG NEXT_PUBLIC_DEFAULT_LANGUAGE=en
ENV NEXT_PUBLIC_DEFAULT_LANGUAGE=${NEXT_PUBLIC_DEFAULT_LANGUAGE}

RUN apt-get update \
  && apt-get install -y --no-install-recommends git ca-certificates \
  && rm -rf /var/lib/apt/lists/*

RUN corepack enable && corepack prepare pnpm@10.34.9 --activate

COPY package.json pnpm-lock.yaml ./
COPY packages ./packages
RUN pnpm install --no-frozen-lockfile

COPY . .
RUN pnpm build:all

FROM node:20-bookworm-slim AS runtime

WORKDIR /app
ENV NODE_ENV=production

RUN corepack enable && corepack prepare pnpm@10.34.9 --activate

COPY --from=build /app/package.json /app/pnpm-lock.yaml ./
COPY --from=build /app/node_modules ./node_modules
COPY --from=build /app/.next ./.next
COPY --from=build /app/dist ./dist
COPY --from=build /app/public ./public
COPY --from=build /app/seeders ./seeders
COPY --from=build /app/config.js ./
COPY --from=build /app/next.config.mjs /app/next-i18next.config.js ./
COPY --from=build /app/package.json ./

EXPOSE 3000 4000
