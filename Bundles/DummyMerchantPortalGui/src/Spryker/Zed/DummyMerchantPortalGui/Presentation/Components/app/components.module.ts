import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';
import { DummyLayoutComponent } from './dummy-layout/dummy-layout.component';
import { DummyLayoutModule } from './dummy-layout/dummy-layout.module';
import { DummyCardComponent } from './dummy-card/dummy-card.component';
import { DummyCardModule } from './dummy-card/dummy-card.module';
import { ButtonModule, ButtonComponent } from '@spryker/button';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([DummyLayoutComponent, DummyCardComponent]),
        DummyLayoutModule,
        DummyCardModule,
    ],
})
export class ComponentsModule {}
