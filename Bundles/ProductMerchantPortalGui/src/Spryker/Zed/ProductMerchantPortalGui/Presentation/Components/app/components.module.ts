import { NgModule } from '@angular/core';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';
import { ButtonModule, ButtonComponent } from '@spryker/button';
import { TabComponent, TabsComponent, TabsModule } from '@spryker/tabs';
import { CardModule, CardComponent } from '@spryker/card';
import { FormItemComponent, FormItemModule } from '@spryker/form-item';
import { InputComponent, InputModule } from '@spryker/input';
import { TextareaComponent, TextareaModule } from '@spryker/textarea';
import { SelectComponent, SelectModule } from '@spryker/select';
import { TreeSelectComponent, TreeSelectModule } from '@spryker/tree-select';
import { CollapsibleComponent, CollapsibleModule } from '@spryker/collapsible';

import { IconGermanyModule, IconUnitedStatesModule } from '../icons';
import { ProductListComponent } from './product-list/product-list.component';
import { ProductListModule } from './product-list/product-list.module';
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
    ];
}
