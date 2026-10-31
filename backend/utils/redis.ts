import { Redis } from "ioredis";

export class RedisSingleton {
  private static instance: Redis;

  private constructor() {}

  public static getInstance(): Redis {
    if (!RedisSingleton.instance) {
      RedisSingleton.instance = new Redis({
        host: process.env.REDIS_HOST || "localhost",
        port: Number(process.env.REDIS_PORT || 6379),
      });
    }
    return RedisSingleton.instance;
  }
}
