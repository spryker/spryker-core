import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CollapsibleModule } from '@spryker/collapsible';
import { UrlHtmlRendererModule } from '@spryker/html-renderer';
import { ManageOrderCollapsibleTotalsComponent } from './manage-order-collapsible-totals.component';

@NgModule({
    imports: [CommonModule, CollapsibleModule, UrlHtmlRendererModule],
    declarations: [ManageOrderCollapsibleTotalsComponent],
    exports: [ManageOrderCollapsibleTotalsComponent],
})
export class ManageOrderCollapsibleTotalsModule {}
