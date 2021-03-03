import { NgModule } from '@angular/core';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';
import { TabComponent, TabsComponent, TabsModule } from '@spryker/tabs';
import { TreeSelectComponent, TreeSelectModule } from '@spryker/tree-select';
import { IconGermanyModule, IconUnitedStatesModule } from '../icons';
import { ProductListComponent } from './product-list/product-list.component';
import { ProductListModule } from './product-list/product-list.module';
import { CardModule, CardComponent } from '@spryker/card';
import { CollapsibleModule, CollapsibleComponent } from '@spryker/collapsible';
import { EditAbstractProductComponent } from './edit-abstract-product/edit-abstract-product.component';
import { EditAbstractProductModule } from './edit-abstract-product/edit-abstract-product.module';
import { EditAbstractProductPricesComponent } from './edit-abstract-product-prices/edit-abstract-product-prices.component';
import { EditAbstractProductPricesModule } from './edit-abstract-product-prices/edit-abstract-product-prices.module';
import { EditAbstractProductAttributesComponent } from './edit-abstract-product-attributes/edit-abstract-product-attributes.component';
import { EditAbstractProductAttributesModule } from './edit-abstract-product-attributes/edit-abstract-product-attributes.module';
import { ImageSetsComponent } from './image-sets/image-sets.component';
import { ImageSetsModule } from './image-sets/image-sets.module';

@NgModule({
    imports: [
        ProductListModule,
        EditAbstractProductModule,
        TabsModule,
        CardModule,
        TreeSelectModule,
        CollapsibleModule,
        EditAbstractProductAttributesModule,
        EditAbstractProductPricesModule,
        IconGermanyModule,
        IconUnitedStatesModule,
        ImageSetsModule,
    ],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        ProductListComponent,
        EditAbstractProductComponent,
        TabComponent,
        TabsComponent,
        CardComponent,
        TreeSelectComponent,
        CollapsibleComponent,
        EditAbstractProductAttributesComponent,
        EditAbstractProductPricesComponent,
        ImageSetsComponent,
    ];
}
