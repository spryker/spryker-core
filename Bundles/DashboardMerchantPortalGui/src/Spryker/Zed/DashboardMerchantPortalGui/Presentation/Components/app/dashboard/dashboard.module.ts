import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HeadlineModule } from '@spryker/headline';

import { DashboardComponent } from './dashboard.component';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [DashboardComponent],
    exports: [DashboardComponent],
})
export class DashboardModule {
}
