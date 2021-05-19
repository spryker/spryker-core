import {
    ChangeDetectionStrategy,
    ChangeDetectorRef,
    Component,
    EventEmitter,
    Input,
    OnChanges,
    Output,
    QueryList,
    SimpleChanges,
    ViewChildren,
    ViewEncapsulation,
} from '@angular/core';
import { ToJson } from '@spryker/utils';
import { InputComponent } from '@spryker/input';
import { IconDeleteModule } from '../../icons';
import {
    ConcreteProductPreview,
    ConcreteProductPreviewErrors,
    ConcreteProductPreviewSuperAttribute,
    ProductAttribute,
} from '../../services/types';
import { ConcreteProductSkuGeneratorFactoryService } from '../../services/concrete-product-sku-generator-factory.service';
import { ConcreteProductNameGeneratorFactoryService } from '../../services/concrete-product-name-generator-factory.service';

@Component({
    selector: 'mp-concrete-products-preview',
    templateUrl: './concrete-products-preview.component.html',
    styleUrls: ['./concrete-products-preview.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    providers: [ConcreteProductSkuGeneratorFactoryService, ConcreteProductNameGeneratorFactoryService],
    host: { class: 'mp-concrete-products-preview' },
})
export class ConcreteProductsPreviewComponent implements OnChanges {
    @Input() @ToJson() attributes: ProductAttribute[] = [];
    @Input() @ToJson() generatedProducts: ConcreteProductPreview[] = [];
    @Input() @ToJson() errors?: ConcreteProductPreviewErrors[];
    @Input() name?: string;
    @Output() generatedProductsChange = new EventEmitter<ConcreteProductPreview[]>();

    @ViewChildren('skuInputRef') skuInputRefs: QueryList<InputComponent>;
    @ViewChildren('nameInputRef') nameInputRefs: QueryList<InputComponent>;

    isAutoGenerateSkuCheckbox = true;
    isAutoGenerateNameCheckbox = true;
    deleteIcon = IconDeleteModule.icon;

    private attributeValues: ConcreteProductPreviewSuperAttribute[][] = [];
    private generatedAttributeValues: ConcreteProductPreviewSuperAttribute[][] = [];
    private initialGeneratedProducts: ConcreteProductPreview[] = [];
    private concreteProductSkuGenerator = this.concreteProductSkuGeneratorFactory.create();
    private concreteProductNameGenerator = this.concreteProductNameGeneratorFactory.create();

    constructor(
        private concreteProductSkuGeneratorFactory: ConcreteProductSkuGeneratorFactoryService,
        private concreteProductNameGeneratorFactory: ConcreteProductNameGeneratorFactoryService,
        private cdr: ChangeDetectorRef,
    ) {}

    ngOnChanges(changes: SimpleChanges) {
        if ('attributes' in changes) {
            this.initialGeneratedProducts = this.generatedProducts?.length ? [...this.generatedProducts] : [];
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

        this.generatedAttributeValues = this.attributeValues.reduce(
            (accum, values) => {
                return accum.flatMap((currentValue) => values.map((value) => [...currentValue, value]));
            },
            [[]],
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
    }

    private hasSkuError(): boolean {
        let hasError = false;

        this.errors.some((error) => {
            if (error.errors?.sku) {
                hasError = true;
            }
        });

        return hasError;
    }

    private hasNameError(): boolean {
        let hasError = false;

        this.errors.some((error) => {
            if (error.errors?.name) {
                hasError = true;
            }
        });

        return hasError;
    }

    generateSku(checked: boolean): void {
        let generatedSku = this.concreteProductSkuGenerator.generate();

        this.skuInputRefs.forEach((item, index) => {
            if (this.generatedProducts[index]) {
                this.generatedProducts = [...this.generatedProducts];
                this.generatedProducts[index].sku = checked ? generatedSku : '';
            }

            if (this.errors?.length && this.errors[index]) {
                this.errors = [...this.errors];
                delete this.errors[index]?.errors?.sku;
            }

            if (this.skuInputRefs.length - 1 !== index) {
                generatedSku = this.concreteProductSkuGenerator.generate(generatedSku);
            }
        });
    }

    generateName(checked: boolean): void {
        let generatedName = this.concreteProductNameGenerator.generate();

        this.nameInputRefs.forEach((item, index) => {
            if (this.generatedProducts[index]) {
                this.generatedProducts = [...this.generatedProducts];
                this.generatedProducts[index].name = checked ? generatedName : '';
            }

            if (this.errors?.length && this.errors[index]) {
                this.errors = [...this.errors];
                delete this.errors[index]?.errors?.name;
            }

            if (this.nameInputRefs.length - 1 !== index) {
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
        this.generatedProducts = [...this.generatedProducts.filter((product, productIndex) => index !== productIndex)];
        this.generatedProductsChange.emit(this.generatedProducts);
    }

    getSkuErrors(index: number, errors: ConcreteProductPreviewErrors[]): string | undefined {
        if (errors?.length && errors[index]) {
            return errors[index]?.errors?.sku;
        }
    }

    getNameErrors(index: number, errors: ConcreteProductPreviewErrors[]): string | undefined {
        if (errors?.length && errors[index]) {
            return errors[index]?.errors?.name;
        }
    }
}
