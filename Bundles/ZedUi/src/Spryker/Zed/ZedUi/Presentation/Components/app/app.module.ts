import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { MpLayoutCenteredComponent } from './mp-layout-centered/mp-layout-centered.component';
import { MpLayoutCenteredModule } from './mp-layout-centered/mp-layout-centered.module';

@NgModule({
    imports: [
        BrowserModule,
        MpLayoutCenteredModule
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'mp-layout-centered',
            component: MpLayoutCenteredComponent
        }
    ];
}
