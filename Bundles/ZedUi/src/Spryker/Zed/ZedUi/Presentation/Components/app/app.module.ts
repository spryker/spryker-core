import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from "@angular/common/http";
import { CustomElementModule } from '@spryker/web-components';

import { LayoutCenteredComponent } from './layout-centered/layout-centered.component';
import { LayoutCenteredModule } from './layout-centered/layout-centered.module';
import { HeaderComponent } from './header/header.component';
import { HeaderModule } from './header/header.module';
import { LayoutMainModule } from './layout-main/layout-main.module';
import { LayoutMainComponent } from './layout-main/layout-main.component';

@NgModule({
    imports: [
        BrowserModule,
        HttpClientModule,
        LayoutCenteredModule,
        LayoutMainModule,
        HeaderModule,
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'mp-layout-centered',
            component: LayoutCenteredComponent,
        },
        {
            selector: 'mp-layout-main',
            component: LayoutMainComponent,
        },
        {
            selector: 'mp-header',
            component: HeaderComponent,
        },
    ];
}
