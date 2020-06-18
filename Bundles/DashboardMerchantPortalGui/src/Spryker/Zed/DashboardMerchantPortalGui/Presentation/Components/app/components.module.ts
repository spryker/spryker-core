import { NgModule } from '@angular/core';
import { CustomElementModule } from '@spryker/web-components';

import { DashboardComponent } from './dashboard/dashboard.component';
import { DashboardModule } from './dashboard/dashboard.module';

@NgModule({
    imports: [DashboardModule],
    providers: [],
})
export class ComponentsModule extends CustomElementModule {
    protected components = [
        {
            selector: 'mp-dashboard',
            component: DashboardComponent,
        },
    ];
}
