import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { MpDashboardModule } from './mp-dashboard/mp-dashboard.module';
import { MpDashboardComponent } from './mp-dashboard/mp-dashboard.component';

@NgModule({
    imports: [
        BrowserModule,
        MpDashboardModule
    ],
    providers: [],
})
export class AppModule extends CustomElementModule {
    protected components = [
        {
            selector: 'mp-dashboard',
            component: MpDashboardComponent
        },
    ];
}
