import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DummyLayoutComponent } from './dummy-layout.component';
import { HeadlineModule } from '@spryker/headline';

@NgModule({
    imports: [CommonModule, HeadlineModule],
    declarations: [DummyLayoutComponent],
    exports: [DummyLayoutComponent],
})
export class DummyLayoutModule {}
