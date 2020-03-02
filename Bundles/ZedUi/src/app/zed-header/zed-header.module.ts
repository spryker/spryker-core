import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ZedHeaderComponent } from './zed-header.component';

@NgModule({
  imports: [CommonModule],
  declarations: [ZedHeaderComponent],
  exports: [ZedHeaderComponent],
})
export class ZedHeaderModule {
}
