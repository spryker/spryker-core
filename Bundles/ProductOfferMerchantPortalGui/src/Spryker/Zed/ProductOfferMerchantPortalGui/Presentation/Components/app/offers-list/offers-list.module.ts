import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ButtonLinkModule } from '@spryker/button';
import { OffersListComponent } from './offers-list.component';
import { OffersListTableModule } from '../offers-list-table/offers-list-table.module';

@NgModule({
    imports: [
        CommonModule,
        OffersListTableModule,
        ButtonLinkModule,
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
