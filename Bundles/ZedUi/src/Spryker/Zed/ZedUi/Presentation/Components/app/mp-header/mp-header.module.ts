import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { MpHeaderComponent } from './mp-header.component';

@NgModule({
  imports: [CommonModule],
  declarations: [MpHeaderComponent],
  exports: [MpHeaderComponent],
})
export class MpHeaderModule {
}
