import {
    ChangeDetectionStrategy,
    ChangeDetectorRef,
    Component,
    EventEmitter,
    Input,
    OnChanges,
    OnInit,
    Output,
    QueryList,
    SimpleChanges,
    ViewChildren,
    ViewEncapsulation,
} from '@angular/core';
import { ToJson } from '@spryker/utils';
import { InputComponent } from '@spryker/input';
import { IconDeleteModule } from '../../icons';
import { ConcreteProductPreview, ConcreteProductPreviewErrors } from './types';
import { ProductAttribute, ProductAttributeValue } from '../product-attributes-selector/types';
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
export class ConcreteProductsPreviewComponent implements OnInit, OnChanges {
    @Input() @ToJson() attributes: ProductAttribute[];
    @Input() @ToJson() generatedProducts: ConcreteProductPreview[];
    @Input() @ToJson() errors?: ConcreteProductPreviewErrors[];
    @Input() name?: string;
    @Output() generatedProductsChange = new EventEmitter<ConcreteProductPreview[]>();

    @ViewChildren('skuInputRef') skuInputRefs: QueryList<InputComponent>;
    @ViewChildren('nameInputRef') nameInputRefs: QueryList<InputComponent>;

    isAutoGenerateSkuCheckbox = true;
    isAutoGenerateNameCheckbox = true;
    deleteIcon = IconDeleteModule.icon;

    private attributeValues: ProductAttributeValue[][] = [];
    private generatedAttributeValues: ProductAttributeValue[][] = [];
    private initialGeneratedProducts: ConcreteProductPreview[] = [];
    private concreteProductSkuGenerator = this.concreteProductSkuGeneratorFactory.create();
    private concreteProductNameGenerator = this.concreteProductNameGeneratorFactory.create();

    constructor(
        private concreteProductSkuGeneratorFactory: ConcreteProductSkuGeneratorFactoryService,
        private concreteProductNameGeneratorFactory: ConcreteProductNameGeneratorFactoryService,
        private cdr: ChangeDetectorRef,
    ) {}

    ngOnInit() {
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
    }

    private generateProductsArray(): void {
        if (!this.attributes.length) {
            return;
        }

        this.attributeValues = this.attributes
            .map((item) =>
                item.attributes?.map((attr) => {
                    return {
                        name: item.name,
                        value: item.value,
                        attribute: {
                            ...attr,
                        },
                    };
                }),
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
            return {
                name: '',
                sku: '',
                superAttributes: [...attrs],
            } as ConcreteProductPreview;
        });
    }

    generateSku(checked: boolean): void {
        let generatedSku = this.concreteProductSkuGenerator.generate();

        this.skuInputRefs.forEach((item, index) => {
            item.value = checked ? generatedSku : '';
            item.disabled = checked;

            if (this.generatedProducts[index]) {
                this.generatedProducts = [...this.generatedProducts];
                this.generatedProducts[index].sku = checked ? generatedSku : '';
            }

            if (this.skuInputRefs.length - 1 !== index) {
                generatedSku = this.concreteProductSkuGenerator.generate(generatedSku);
            }
        });
    }

    generateName(checked: boolean): void {
        let generatedName = this.concreteProductNameGenerator.generate();

        this.nameInputRefs.forEach((item, index) => {
            item.value = checked ? generatedName : '';
            item.disabled = checked;

            if (this.generatedProducts[index]) {
                this.generatedProducts = [...this.generatedProducts];
                this.generatedProducts[index].name = checked ? generatedName : '';
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

    hasSkuError(): boolean {
        let hasError = false;
        this.errors.forEach((error) => {
            if (error.errors?.sku) {
                hasError = true;
            }
        });

        return hasError;
    }

    hasNameError(): boolean {
        let hasError = false;
        this.errors.forEach((error) => {
            if (error.errors?.name) {
                hasError = true;
            }
        });

        return hasError;
    }
}
