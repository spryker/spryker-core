import {
    ChangeDetectionStrategy,
    ChangeDetectorRef,
    Component,
    EventEmitter,
    Input,
    OnChanges,
    Output,
    SimpleChanges,
    ViewEncapsulation,
} from '@angular/core';
import { ToJson } from '@spryker/utils';
import { ButtonSize } from '@spryker/button';
import { IconDeleteModule, IconNoDataModule } from '../../icons';
import {
    ConcreteProductPreview,
    ConcreteProductPreviewErrors,
    ConcreteProductPreviewSuperAttribute,
    ProductAttribute,
} from '../../services/types';
import { ConcreteProductSkuGeneratorFactoryService } from '../../services/concrete-product-sku-generator-factory.service';
import { ConcreteProductNameGeneratorFactoryService } from '../../services/concrete-product-name-generator-factory.service';
import { ProductAttributesFinderService } from '../../services/product-attributes-finder.service';

@Component({
    selector: 'mp-concrete-products-preview',
    templateUrl: './concrete-products-preview.component.html',
    styleUrls: ['./concrete-products-preview.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    providers: [
        ConcreteProductSkuGeneratorFactoryService,
        ConcreteProductNameGeneratorFactoryService,
        ProductAttributesFinderService,
    ],
    host: { class: 'mp-concrete-products-preview' },
})
export class ConcreteProductsPreviewComponent implements OnChanges {
    @Input() @ToJson() attributes: ProductAttribute[] = [];
    @Input() @ToJson() generatedProducts: ConcreteProductPreview[] = [];
    @Input() @ToJson() existingProducts?: ConcreteProductPreview[];
    @Input() @ToJson() errors?: ConcreteProductPreviewErrors[];
    @Input() name?: string;
    @Output() generatedProductsChange = new EventEmitter<ConcreteProductPreview[]>();
    @Output() attributesChange = new EventEmitter<ProductAttribute[]>();

    areAttributesComplete = false;
    isAutoGenerateSkuCheckbox = true;
    isAutoGenerateNameCheckbox = true;
    deleteIcon = IconDeleteModule.icon;
    noDataIcon = IconNoDataModule.icon;
    deleteIconSize = ButtonSize.Medium;

    private attributeValues: ConcreteProductPreviewSuperAttribute[][] = [];
    private generatedAttributeValues: ConcreteProductPreviewSuperAttribute[][] = [];
    private initialGeneratedProducts: ConcreteProductPreview[] = [];
    private concreteProductSkuGenerator = this.concreteProductSkuGeneratorFactory.create();
    private concreteProductNameGenerator = this.concreteProductNameGeneratorFactory.create();

    constructor(
        private concreteProductSkuGeneratorFactory: ConcreteProductSkuGeneratorFactoryService,
        private concreteProductNameGeneratorFactory: ConcreteProductNameGeneratorFactoryService,
        private cdr: ChangeDetectorRef,
        private productAttributesFinderService: ProductAttributesFinderService,
    ) {}

    ngOnChanges(changes: SimpleChanges) {
        if ('attributes' in changes) {
            this.areAttributesComplete =
                this.attributes?.every?.((attribute) => attribute?.attributes.length > 0) ?? false;
            this.initialGeneratedProducts = this.generatedProducts?.length ? [...this.generatedProducts] : [];

            if (this.areAttributesComplete) {
                this.generateProductsArray();

                setTimeout(() => {
                    if (this.isAutoGenerateSkuCheckbox) {
                        this.generateSku(this.isAutoGenerateSkuCheckbox);
                    }

                    if (this.isAutoGenerateNameCheckbox) {
                        this.generateName(this.isAutoGenerateNameCheckbox);
                    }

                    this.cdr.markForCheck();
                });
            }

            if (!changes.attributes.firstChange) {
                this.errors = [];
            }
        }

        if ('generatedProducts' in changes) {
            this.initialGeneratedProducts = this.generatedProducts?.length ? [...this.generatedProducts] : [];

            if (!this.initialGeneratedProducts?.length) {
                return;
            }

            this.initialGeneratedProducts.some((generatedProduct) => {
                if (!generatedProduct.sku.length || this.hasSkuError()) {
                    this.isAutoGenerateSkuCheckbox = false;
                }

                if (!generatedProduct.name.length || this.hasNameError()) {
                    this.isAutoGenerateNameCheckbox = false;
                }
            });
            this.generatedProducts = [...this.initialGeneratedProducts];
        }
    }

    private generateProductsArray(): void {
        if (!this.attributes.length) {
            return;
        }

        this.attributeValues = this.attributes
            .map((item) =>
                item.attributes?.map((attr) => ({
                    name: item.name,
                    value: item.value,
                    attribute: {
                        ...attr,
                    },
                })),
            )
            .filter((item) => item?.length);

        if (!this.attributeValues.length) {
            this.generatedProducts = [];
            this.generatedProductsChange.emit(this.generatedProducts);

            return;
        }

        const existingAttributeCollection = this.productAttributesFinderService.getAttributeCollection(
            this.existingProducts ?? [],
        );

        this.generatedAttributeValues = this.attributeValues
            .reduce(
                (accum, values) => accum.flatMap((currentValue) => values.map((value) => [...currentValue, value])),
                [[]],
            )
            .filter((attribute: ConcreteProductPreviewSuperAttribute[]) =>
                this.productAttributesFinderService.isAttributeNew(attribute, existingAttributeCollection),
            );

        this.generatedProducts = this.generatedAttributeValues.map((attrs) => {
            const existingGeneratedProduct = this.generatedProducts?.find((product) => {
                if (product?.superAttributes.length !== attrs.length) {
                    return false;
                }

                return product?.superAttributes.every((superAttr) => {
                    /* tslint:disable:no-shadowed-variable */
                    const attr = attrs.find((attr) => attr.value === superAttr.value);
                    /* tslint:enable */

                    return attr.attribute.value === superAttr.attribute.value;
                });
            });

            if (existingGeneratedProduct) {
                return existingGeneratedProduct;
            } else {
                return {
                    name: '',
                    sku: '',
                    superAttributes: [...attrs],
                } as ConcreteProductPreview;
            }
        });

        this.generatedProductsChange.emit(this.generatedProducts);
    }

    private hasSkuError(): boolean {
        return this.errors.some((error) => error.errors?.sku);
    }

    private hasNameError(): boolean {
        return this.errors.some((error) => error.errors?.name);
    }

    private updateAttributesOnDelete(): void {
        const generatedProductsHashedObject = Object.create(null);
        const superAttrsLength = this.attributes.length;
        let isAttrsUpdated = false;

        this.generatedProducts.map((product) => {
            product.superAttributes.map((productAttr) => {
                generatedProductsHashedObject[
                    this.computeAttrsHash(productAttr.value, productAttr.attribute.value)
                ] = true;
            });
        });

        this.attributes = this.attributes
            .map((superAttr) => ({ ...superAttr }))
            .filter((superAttr) => {
                const attrsLength = superAttr.attributes.length;

                superAttr.attributes = superAttr.attributes.filter((attr) => {
                    return this.computeAttrsHash(superAttr.value, attr.value) in generatedProductsHashedObject;
                });

                if (attrsLength !== superAttr.attributes.length) {
                    isAttrsUpdated = true;
                }

                return superAttr.attributes.length > 0;
            });

        if (superAttrsLength !== this.attributes.length) {
            isAttrsUpdated = true;
        }

        if (isAttrsUpdated) {
            this.attributesChange.emit(this.attributes);
        }
    }

    private computeAttrsHash(superAttr: string, attr: string): string {
        return `${superAttr}&${attr}`;
    }

    generateSku(checked: boolean): void {
        let generatedSku = this.concreteProductSkuGenerator.generate();

        this.generatedProducts.forEach((item, index) => {
            if (this.generatedProducts[index]) {
                this.generatedProducts = [...this.generatedProducts];
                this.generatedProducts[index].sku = checked ? generatedSku : '';
            }

            if (this.errors?.length && this.errors[index]) {
                this.errors = [...this.errors];
                delete this.errors[index]?.errors?.sku;
            }

            if (this.generatedProducts.length - 1 !== index) {
                generatedSku = this.concreteProductSkuGenerator.generate(generatedSku);
            }
        });
    }

    generateName(checked: boolean): void {
        let generatedName = this.concreteProductNameGenerator.generate();

        this.generatedProducts.forEach((item, index) => {
            if (this.generatedProducts[index]) {
                this.generatedProducts = [...this.generatedProducts];
                this.generatedProducts[index].name = checked ? generatedName : '';
            }

            if (this.errors?.length && this.errors[index]) {
                this.errors = [...this.errors];
                delete this.errors[index]?.errors?.name;
            }

            if (this.generatedProducts.length - 1 !== index) {
                generatedName = this.concreteProductNameGenerator.generate(generatedName);
            }
        });
    }

    skuChange(value: string, index: number): void {
        this.generatedProducts = [...this.generatedProducts];
        this.generatedProducts[index].sku = value;
        this.generatedProductsChange.emit(this.generatedProducts);
    }

    nameChange(value: string, index: number): void {
        this.generatedProducts = [...this.generatedProducts];
        this.generatedProducts[index].name = value;
        this.generatedProductsChange.emit(this.generatedProducts);
    }

    delete(index: number): void {
        this.generatedProducts = this.generatedProducts.filter((product, productIndex) => index !== productIndex);
        this.updateAttributesOnDelete();

        if (this.errors?.length) {
            this.errors = this.errors.filter((error, errorIndex) => errorIndex !== index);
        }

        this.generatedProductsChange.emit(this.generatedProducts);
    }

    getSkuErrors(index: number, errors: ConcreteProductPreviewErrors[]): string | undefined {
        return errors?.[index]?.errors?.sku;
    }

    getNameErrors(index: number, errors: ConcreteProductPreviewErrors[]): string | undefined {
        return errors?.[index]?.errors?.name;
    }

    trackByAttributes(index: number, data: ConcreteProductPreview): string {
        return data.superAttributes.reduce((acc, item) => `${acc}/${item.value}&${item.attribute.value}`, '');
    }
}
