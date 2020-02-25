import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { ZedHeaderComponent } from './zed-header/zed-header.component';
import { ZedHeaderModule } from './zed-header/zed-header.module';
import { ZedLayoutMainModule } from './zed-layout-main/zed-layout-main.module';
import { ZedLayoutMainComponent } from './zed-layout-main/zed-layout-main.component';

@NgModule({
    imports: [
        BrowserModule,
        ZedLayoutMainModule,
        ZedHeaderModule
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'zed-layout-main',
            component: ZedLayoutMainComponent
        },
        {
            selector: 'zed-header',
            component: ZedHeaderComponent
        }
    ];
}
