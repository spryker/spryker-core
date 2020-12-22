import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ManageOrderComponent } from './manage-order.component';
import { ManageOrderStatsBlockComponent } from './manage-order-stats-block/manage-order-stats-block.component';
import { ManageOrderTotalsComponent } from './manage-order-totals/manage-order-totals.component';
import { ManageOrderCollapsibleTotalsComponent } from './manage-order-collapsible-totals/manage-order-collapsible-totals.component';
import { IconModule } from '@spryker/icon';
import { IconInfoModule } from '@spryker/icon/icons';
import { CollapsibleModule } from '@spryker/collapsible';
import { UrlHtmlRendererModule } from '@spryker/html-renderer';

@NgModule({
    imports: [CommonModule, IconModule, IconInfoModule, CollapsibleModule, UrlHtmlRendererModule],
    declarations: [
        ManageOrderComponent,
        ManageOrderStatsBlockComponent,
        ManageOrderTotalsComponent,
        ManageOrderCollapsibleTotalsComponent,
    ],
    exports: [
        ManageOrderComponent,
        ManageOrderStatsBlockComponent,
        ManageOrderTotalsComponent,
        ManageOrderCollapsibleTotalsComponent,
    ],
})
export class ManageOrderModule {}
