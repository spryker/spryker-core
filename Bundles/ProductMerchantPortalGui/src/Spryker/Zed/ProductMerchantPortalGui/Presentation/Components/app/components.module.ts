import { NgModule } from '@angular/core';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';
import { ButtonModule, ButtonComponent } from '@spryker/button';
import { TabComponent, TabsComponent, TabsModule } from '@spryker/tabs';
import { TextareaComponent, TextareaModule } from '@spryker/textarea';
import { TreeSelectComponent, TreeSelectModule } from '@spryker/tree-select';
import { IconGermanyModule, IconUnitedStatesModule } from '../icons';
import { ProductListComponent } from './product-list/product-list.component';
import { ProductListModule } from './product-list/product-list.module';
import { CardModule, CardComponent } from '@spryker/card';
import { SelectModule, SelectComponent } from '@spryker/select';
import { InputModule, InputComponent } from '@spryker/input';
import { CollapsibleModule, CollapsibleComponent } from '@spryker/collapsible';
import { FormItemModule, FormItemComponent } from '@spryker/form-item';
import { ButtonActionComponent, ButtonActionModule } from '@spryker/button.action';
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

@NgModule({
    imports: [
        ProductListModule,
        ButtonModule,
        EditAbstractProductModule,
        TabsModule,
        CardModule,
        InputModule,
        FormItemModule,
        SelectModule,
        TreeSelectModule,
        CollapsibleModule,
        EditAbstractProductAttributesModule,
        EditAbstractProductPricesModule,
        IconGermanyModule,
        IconUnitedStatesModule,
        TextareaModule,
        ImageSetsModule,
        BulkEditProductVariantsModule,
        EditAbstractProductVariantsModule,
        ButtonActionModule,
    ],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        ProductListComponent,
        ButtonComponent,
        EditAbstractProductComponent,
        TabComponent,
        TabsComponent,
        CardComponent,
        FormItemComponent,
        InputComponent,
        SelectComponent,
        TreeSelectComponent,
        CollapsibleComponent,
        EditAbstractProductAttributesComponent,
        EditAbstractProductPricesComponent,
        TextareaComponent,
        ImageSetsComponent,
        BulkEditProductVariantsComponent,
        EditAbstractProductVariantsComponent,
        ButtonActionComponent,
    ];
}
