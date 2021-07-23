import { NgModule } from '@angular/core';
import { CacheModule } from '@spryker/cache';
import { StaticCacheStrategy, StaticCacheStrategyModule } from '@spryker/cache.static';

@NgModule({
    imports: [
        CacheModule.withStrategies({
            static: StaticCacheStrategy,
        }),
        StaticCacheStrategyModule,
    ],
})
export class DefaultCacheConfigModule {}
