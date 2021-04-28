import { NgModule } from '@angular/core';
import { CustomElementModule, WebComponentDefs } from '@spryker/web-components';
import { TabComponent, TabsComponent, TabsModule } from '@spryker/tabs';
import { TreeSelectComponent, TreeSelectModule } from '@spryker/tree-select';
import { IconPlusModule } from '@spryker/icon/icons';
import { IconGermanyModule, IconUnitedStatesModule, IconDeleteModule, IconNoDataModule } from '../icons';
import { ProductListComponent } from './product-list/product-list.component';
import { ProductListModule } from './product-list/product-list.module';
import { CardModule, CardComponent } from '@spryker/card';
import { ChipsModule, ChipsComponent } from '@spryker/chips';
import { DateRangePickerModule, DateRangePickerComponent } from '@spryker/date-picker';
import { CollapsibleModule, CollapsibleComponent } from '@spryker/collapsible';
<<<<<<< HEAD
import { ChipsComponent, ChipsModule } from '@spryker/chips';
import { FormItemModule, FormItemComponent } from '@spryker/form-item';
import { IconModule, IconComponent } from '@spryker/icon';
import { RadioModule, RadioComponent, RadioGroupComponent } from '@spryker/radio';
import { ButtonActionComponent, ButtonActionModule } from '@spryker/button.action';
=======
>>>>>>> master
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
<<<<<<< HEAD
import { CreateAbstractProductModule } from './create-abstract-product/create-abstract-product.module';
import { CreateAbstractProductComponent } from './create-abstract-product/create-abstract-product.component';
import { CreateSingleConcreteProductModule } from './create-single-concrete-product/create-single-concrete-product.module';
import { CreateSingleConcreteProductComponent } from './create-single-concrete-product/create-single-concrete-product.component';
import { AutogenerateInputModule } from './autogenerate-input/autogenerate-input.module';
import { AutogenerateInputComponent } from './autogenerate-input/autogenerate-input.component';
import { ConcreteProductsPreviewModule } from './concrete-products-preview/concrete-products-preview.module';
import { ConcreteProductsPreviewComponent } from './concrete-products-preview/concrete-products-preview.component';
import { ProductAttributesSelectorModule } from './product-attributes-selector/product-attributes-selector.module';
import { ProductAttributesSelectorComponent } from './product-attributes-selector/product-attributes-selector.component';
import { CreateMultiConcreteProductModule } from './create-multi-concrete-product/create-multi-concrete-product.module';
import { CreateMultiConcreteProductComponent } from './create-multi-concrete-product/create-multi-concrete-product.component';
import { ConcreteProductGeneratorDataModule } from './concrete-product-generator-data/concrete-product-generator-data.module';
import { ConcreteProductGeneratorDataComponent } from './concrete-product-generator-data/concrete-product-generator-data.component';
=======
import { EditConcreteProductPricesComponent } from './edit-concrete-product-prices/edit-concrete-product-prices.component';
import { EditConcreteProductPricesModule } from './edit-concrete-product-prices/edit-concrete-product-prices.module';
import { EditConcreteProductAttributesComponent } from './edit-concrete-product-attributes/edit-concrete-product-attributes.component';
import { EditConcreteProductAttributesModule } from './edit-concrete-product-attributes/edit-concrete-product-attributes.module';
import { EditConcreteProductComponent } from './edit-concrete-product/edit-concrete-product.component';
import { EditConcreteProductModule } from './edit-concrete-product/edit-concrete-product.module';
>>>>>>> master

@NgModule({
    imports: [
        TabsModule,
        CardModule,
<<<<<<< HEAD
        InputModule,
        IconModule,
        FormItemModule,
        SelectModule,
        TreeSelectModule,
        CollapsibleModule,
        ChipsModule,
        EditAbstractProductAttributesModule,
        EditAbstractProductPricesModule,
        IconGermanyModule,
        IconUnitedStatesModule,
        IconDeleteModule,
        IconNoDataModule,
        IconPlusModule,
        TextareaModule,
        RadioModule,
=======
        TreeSelectModule,
        CollapsibleModule,
        ChipsModule,
        DateRangePickerModule,
        IconGermanyModule,
        IconUnitedStatesModule,
        ProductListModule,
>>>>>>> master
        ImageSetsModule,
        EditAbstractProductAttributesModule,
        EditAbstractProductPricesModule,
        EditAbstractProductModule,
        BulkEditProductVariantsModule,
        EditAbstractProductVariantsModule,
<<<<<<< HEAD
        ButtonActionModule,
        CreateAbstractProductModule,
        CreateSingleConcreteProductModule,
        AutogenerateInputModule,
        ConcreteProductsPreviewModule,
        ProductAttributesSelectorModule,
        CreateMultiConcreteProductModule,
        ConcreteProductGeneratorDataModule,
=======
        EditConcreteProductPricesModule,
        EditConcreteProductAttributesModule,
        EditConcreteProductModule,
>>>>>>> master
    ],
})
export class ComponentsModule extends CustomElementModule {
    protected components: WebComponentDefs = [
        TabComponent,
        TabsComponent,
        CardComponent,
<<<<<<< HEAD
        FormItemComponent,
        InputComponent,
        IconComponent,
        SelectComponent,
        TreeSelectComponent,
        CollapsibleComponent,
        ChipsComponent,
        EditAbstractProductAttributesComponent,
        EditAbstractProductPricesComponent,
        TextareaComponent,
        RadioComponent,
        RadioGroupComponent,
        ImageSetsComponent,
        BulkEditProductVariantsComponent,
        EditAbstractProductVariantsComponent,
        ButtonActionComponent,
        CreateAbstractProductComponent,
        CreateSingleConcreteProductComponent,
        AutogenerateInputComponent,
        ConcreteProductsPreviewComponent,
        ProductAttributesSelectorComponent,
        CreateMultiConcreteProductComponent,
        ConcreteProductGeneratorDataComponent,
=======
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
>>>>>>> master
    ];
}
