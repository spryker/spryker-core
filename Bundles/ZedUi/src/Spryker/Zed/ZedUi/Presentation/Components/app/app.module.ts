import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { ZedLayoutCenteredComponent } from './zed-layout-centered/zed-layout-centered.component';
import { ZedLayoutCenteredModule } from './zed-layout-centered/zed-layout-centered.module';

@NgModule({
    imports: [
        BrowserModule,
        ZedLayoutCenteredModule
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'zed-layout-centered',
            component: ZedLayoutCenteredComponent
        }
    ];
}
