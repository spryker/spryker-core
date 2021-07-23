import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';
import { TabComponent, TabsComponent, TabsModule } from '@spryker/tabs';
import { TreeSelectComponent, TreeSelectModule } from '@spryker/tree-select';
import { IconPlusModule } from '@spryker/icon/icons';
import { IconGermanyModule, IconUnitedStatesModule, IconDeleteModule, IconNoDataModule } from '../icons';
import { IconModule, IconComponent } from '@spryker/icon';
import { RadioModule, RadioComponent, RadioGroupComponent } from '@spryker/radio';
import { ButtonActionComponent, ButtonActionModule } from '@spryker/button.action';
import { CardModule, CardComponent } from '@spryker/card';
import { ChipsModule, ChipsComponent } from '@spryker/chips';
import { DateRangePickerModule, DateRangePickerComponent } from '@spryker/date-picker';
import { ContentToggleComponent, ContentToggleModule } from '@mp/zed-ui';

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
import { CreateAbstractProductComponent } from './create-abstract-product/create-abstract-product.component';
import { CreateAbstractProductModule } from './create-abstract-product/create-abstract-product.module';
import { CreateSingleConcreteProductComponent } from './create-single-concrete-product/create-single-concrete-product.component';
import { CreateSingleConcreteProductModule } from './create-single-concrete-product/create-single-concrete-product.module';
import { AutogenerateInputComponent } from './autogenerate-input/autogenerate-input.component';
import { AutogenerateInputModule } from './autogenerate-input/autogenerate-input.module';
import { CreateMultiConcreteProductComponent } from './create-multi-concrete-product/create-multi-concrete-product.component';
import { CreateMultiConcreteProductModule } from './create-multi-concrete-product/create-multi-concrete-product.module';
import { ConcreteProductGeneratorDataComponent } from './concrete-product-generator-data/concrete-product-generator-data.component';
import { ConcreteProductGeneratorDataModule } from './concrete-product-generator-data/concrete-product-generator-data.module';
import { EditConcreteProductImageSetsComponent } from './edit-concrete-product-image-sets/edit-concrete-product-image-sets.component';
import { EditConcreteProductImageSetsModule } from './edit-concrete-product-image-sets/edit-concrete-product-image-sets.module';
import { ExistingConcreteProductGeneratorDataModule } from './existing-concrete-product-generator-data/existing-concrete-product-generator-data.module';
import { ExistingConcreteProductGeneratorDataComponent } from './existing-concrete-product-generator-data/existing-concrete-product-generator-data.component';
import { CreateConcreteProductsModule } from './create-concrete-products/create-concrete-products.module';
import { CreateConcreteProductsComponent } from './create-concrete-products/create-concrete-products.component';
import { CreateConcreteProductsOverlayModule } from './create-concrete-products-overlay/create-concrete-products-overlay.module';
import { CreateConcreteProductsOverlayComponent } from './create-concrete-products-overlay/create-concrete-products-overlay.component';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            DateRangePickerComponent,
            TabComponent,
            TabsComponent,
            CardComponent,
            IconComponent,
            TreeSelectComponent,
            ChipsComponent,
            RadioComponent,
            RadioGroupComponent,
            ImageSetsComponent,
            ButtonActionComponent,
            ContentToggleComponent,
            EditAbstractProductAttributesComponent,
            EditAbstractProductPricesComponent,
            EditConcreteProductPricesComponent,
            EditConcreteProductAttributesComponent,
            EditConcreteProductComponent,
            ProductListComponent,
            EditAbstractProductComponent,
            BulkEditProductVariantsComponent,
            EditAbstractProductVariantsComponent,
            CreateAbstractProductComponent,
            CreateSingleConcreteProductComponent,
            AutogenerateInputComponent,
            CreateMultiConcreteProductComponent,
            ConcreteProductGeneratorDataComponent,
            EditConcreteProductImageSetsComponent,
            ExistingConcreteProductGeneratorDataComponent,
            CreateConcreteProductsComponent,
            CreateConcreteProductsOverlayComponent,
        ]),
        DateRangePickerModule,
        TabsModule,
        CardModule,
        IconModule,
        TreeSelectModule,
        ChipsModule,
        IconGermanyModule,
        IconUnitedStatesModule,
        IconDeleteModule,
        IconNoDataModule,
        IconPlusModule,
        RadioModule,
        ButtonActionModule,
        ContentToggleModule,
        ProductListModule,
        ImageSetsModule,
        EditAbstractProductAttributesModule,
        EditAbstractProductPricesModule,
        EditConcreteProductPricesModule,
        EditConcreteProductAttributesModule,
        EditConcreteProductModule,
        EditConcreteProductImageSetsModule,
        ProductListModule,
        EditAbstractProductModule,
        BulkEditProductVariantsModule,
        EditAbstractProductVariantsModule,
        CreateAbstractProductModule,
        CreateSingleConcreteProductModule,
        AutogenerateInputModule,
        CreateMultiConcreteProductModule,
        ConcreteProductGeneratorDataModule,
        ExistingConcreteProductGeneratorDataModule,
        CreateConcreteProductsModule,
        CreateConcreteProductsOverlayModule,
    ],
})
export class ComponentsModule {}
