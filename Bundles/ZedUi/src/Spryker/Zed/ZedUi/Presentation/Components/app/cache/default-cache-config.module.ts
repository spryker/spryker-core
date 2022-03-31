import { NgModule } from '@angular/core';
import { CacheModule } from '@spryker/cache';
import { StaticCacheStrategy, StaticCacheStrategyModule, StaticCacheStrategyConfig } from '@spryker/cache.static';

declare module '@spryker/cache' {
    interface CacheStrategyRegistry {
        static: StaticCacheStrategyConfig;
    }
}

@NgModule({
    imports: [
        CacheModule.withStrategies({
            static: StaticCacheStrategy,
        }),
        StaticCacheStrategyModule,
    ],
})
export class DefaultCacheConfigModule {}
