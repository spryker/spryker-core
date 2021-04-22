import { NgModule } from '@angular/core';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';
import { TabComponent, TabsComponent, TabsModule } from '@spryker/tabs';
import { TreeSelectComponent, TreeSelectModule } from '@spryker/tree-select';
import { IconGermanyModule, IconUnitedStatesModule } from '../icons';
import { ProductListComponent } from './product-list/product-list.component';
import { ProductListModule } from './product-list/product-list.module';
import { CardModule, CardComponent } from '@spryker/card';
import { ChipsModule, ChipsComponent } from '@spryker/chips';
import { DateRangePickerModule, DateRangePickerComponent } from '@spryker/date-picker';
import { CollapsibleModule, CollapsibleComponent } from '@spryker/collapsible';
import { EditAbstractProductComponent } from './edit-abstract-product/edit-abstract-product.component';
import { EditAbstractProductModule } from './edit-abstract-product/edit-abstract-product.module';
import { EditAbstractProductPricesComponent } from './edit-abstract-product-prices/edit-abstract-product-prices.component';
import { EditAbstractProductPricesModule } from './edit-abstract-product-prices/edit-abstract-product-prices.module';
import { EditAbstractProductAttributesComponent } from './edit-abstract-product-attributes/edit-abstract-product-attributes.component';
import { EditAbstractProductAttributesModule } from './edit-abstract-product-attributes/edit-abstract-product-attributes.module';
import { ImageSetsComponent } from './image-sets/image-sets.component';
import { ImageSetsModule } from './image-sets/image-sets.module';
import { EditAbstractProductVariantsComponent } from './edit-abstract-product-variants/edit-abstract-product-variants.component';
import { EditAbstractProductVariantsModule } from './edit-abstract-product-variants/edit-abstract-product-variants.module';
import { BulkEditProductVariantsComponent } from './bulk-edit-product-variants/bulk-edit-product-variants.component';
import { BulkEditProductVariantsModule } from './bulk-edit-product-variants/bulk-edit-product-variants.module';
import { EditConcreteProductPricesComponent } from './edit-concrete-product-prices/edit-concrete-product-prices.component';
import { EditConcreteProductPricesModule } from './edit-concrete-product-prices/edit-concrete-product-prices.module';
import { EditConcreteProductAttributesComponent } from './edit-concrete-product-attributes/edit-concrete-product-attributes.component';
import { EditConcreteProductAttributesModule } from './edit-concrete-product-attributes/edit-concrete-product-attributes.module';
import { EditConcreteProductComponent } from './edit-concrete-product/edit-concrete-product.component';
import { EditConcreteProductModule } from './edit-concrete-product/edit-concrete-product.module';

@NgModule({
    imports: [
        TabsModule,
        CardModule,
        TreeSelectModule,
        CollapsibleModule,
        ChipsModule,
        DateRangePickerModule,
        IconGermanyModule,
        IconUnitedStatesModule,
        ProductListModule,
        ImageSetsModule,
        EditAbstractProductAttributesModule,
        EditAbstractProductPricesModule,
        EditAbstractProductModule,
        BulkEditProductVariantsModule,
        EditAbstractProductVariantsModule,
        EditConcreteProductPricesModule,
        EditConcreteProductAttributesModule,
        EditConcreteProductModule,
    ],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        TabComponent,
        TabsComponent,
        CardComponent,
        TreeSelectComponent,
        CollapsibleComponent,
        ChipsComponent,
        DateRangePickerComponent,
        ProductListComponent,
        ImageSetsComponent,
        EditAbstractProductAttributesComponent,
        EditAbstractProductPricesComponent,
        EditAbstractProductComponent,
        BulkEditProductVariantsComponent,
        EditAbstractProductVariantsComponent,
        EditConcreteProductPricesComponent,
        EditConcreteProductAttributesComponent,
        EditConcreteProductComponent,
    ];
}
