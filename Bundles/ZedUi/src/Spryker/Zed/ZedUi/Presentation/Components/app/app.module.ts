import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from "@angular/common/http";
import { CustomElementModule } from '@spryker/web-components';

import { MpLayoutCenteredComponent } from './mp-layout-centered/mp-layout-centered.component';
import { MpLayoutCenteredModule } from './mp-layout-centered/mp-layout-centered.module';
import { MpHeaderComponent } from './mp-header/mp-header.component';
import { MpHeaderModule } from './mp-header/mp-header.module';
import { MpLayoutMainModule } from './mp-layout-main/mp-layout-main.module';
import { MpLayoutMainComponent } from './mp-layout-main/mp-layout-main.component';

@NgModule({
    imports: [
        BrowserModule,
        HttpClientModule,
        MpLayoutCenteredModule,
        MpLayoutMainModule,
        MpHeaderModule,
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'mp-layout-centered',
            component: MpLayoutCenteredComponent,
        },
        {
            selector: 'mp-layout-main',
            component: MpLayoutMainComponent,
        },
        {
            selector: 'mp-header',
            component: MpHeaderComponent,
        },
    ];
}
