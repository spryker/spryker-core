import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { OffersListComponent } from './offers-list.component';
import { OffersListTableModule } from '../offers-list-table/offers-list-table.module';

@NgModule({
    imports: [
        CommonModule,
        OffersListTableModule,
    ],
    declarations: [
        OffersListComponent,
    ],
    exports: [
        OffersListComponent,
    ],
})
export class OffersListModule {
}
