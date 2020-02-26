import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MpDashboardComponent } from './mp-dashboard.component';

@NgModule({
  imports: [CommonModule],
  declarations: [MpDashboardComponent],
  exports: [MpDashboardComponent],
})
export class MpDashboardModule {
}
