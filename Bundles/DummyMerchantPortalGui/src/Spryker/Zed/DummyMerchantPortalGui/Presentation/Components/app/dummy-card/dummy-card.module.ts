import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DummyCardComponent } from './dummy-card.component';
import { CardModule } from '@spryker/card';

@NgModule({
    imports: [CommonModule, CardModule],
    declarations: [DummyCardComponent],
    exports: [DummyCardComponent],
})
export class DummyCardModule {}
