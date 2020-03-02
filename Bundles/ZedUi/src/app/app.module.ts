import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { ZedLayoutCentralComponent } from './zed-layout-central/zed-layout-central.component';
import { ZedLayoutCentralModule } from './zed-layout-central/zed-layout-central.module';

@NgModule({
    imports: [
        BrowserModule,
        ZedLayoutCentralModule
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'zed-layout-central',
            component: ZedLayoutCentralComponent
        }
    ];
}
