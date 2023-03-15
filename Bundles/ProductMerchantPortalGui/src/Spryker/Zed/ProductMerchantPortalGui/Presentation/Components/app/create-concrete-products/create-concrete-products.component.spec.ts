import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { ScrollingModule } from '@angular/cdk/scrolling';
import { InvokeModule } from '@spryker/utils';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { CreateConcreteProductsComponent } from './create-concrete-products.component';
import { ConcreteProductAttributesSelectorComponent } from '../concrete-product-attributes-selector/concrete-product-attributes-selector.component';
import { ConcreteProductsPreviewComponent } from '../concrete-products-preview/concrete-products-preview.component';
import { ConcreteProductGeneratorDataService } from '../../services/concrete-product-generator-data.service';

const mockProductsName = 'Products Name';
const mockAttributesName = 'Attributes Name';
const mockAttributesPlaceholder = 'Attributes Placeholder';
const mockAttributes = [
    {
        name: 'name1',
        value: 'value1',
        attributes: [
            {
                name: 'name11',
                value: 'value11',
            },
            {
                name: 'name12',
                value: 'value12',
            },
        ],
    },
    {
        name: 'name2',
        value: 'value2',
        attributes: [
            {
                name: 'name21',
                value: 'value21',
            },
        ],
    },
];
const mockAttributeErrors = [
    {
        error: 'attribute error',
    },
];
const mockSelectedAttributes = [
    {
        name: 'name1',
        value: 'value1',
        attributes: [
            {
                name: 'name11',
                value: 'value11',
            },
        ],
    },
];
const mockUpdatedSelectedAttributes = [
    ...mockSelectedAttributes,
    {
        name: 'name2',
        value: 'value2',
        attributes: [
            {
                name: 'name21',
                value: 'value21',
            },
        ],
    },
];
const mockExistingProducts = [
    {
        name: '',
        sku: '',
        superAttributes: [
            {
                name: 'name1',
                value: 'value1',
                attribute: {
                    name: 'name12',
                    value: 'value12',
                },
            },
            {
                name: 'name2',
                value: 'value2',
                attribute: {
                    name: 'name21',
                    value: 'value21',
                },
            },
        ],
    },
];
const mockGeneratedProducts = [
    {
        name: '',
        sku: '',
        superAttributes: [
            {
                name: 'name1',
                value: 'value1',
                attribute: {
                    name: 'name11',
                    value: 'value11',
                },
            },
            {
                name: 'name2',
                value: 'value2',
                attribute: {
                    name: 'name21',
                    value: 'value21',
                },
            },
        ],
    },
    {
        name: '',
        sku: '',
        superAttributes: [
            {
                name: 'name1',
                value: 'value1',
                attribute: {
                    name: 'name12',
                    value: 'value12',
                },
            },
            {
                name: 'name2',
                value: 'value2',
                attribute: {
                    name: 'name21',
                    value: 'value21',
                },
            },
        ],
    },
];
const mockGeneratedProductErrors = [
    {
        fields: {
            name: '',
            sku: '123',
        },
        errors: {
            name: 'This value should not be blank.',
            sku: 'SKU Prefix already exists',
        },
    },
    {},
];

describe('CreateConcreteProductsComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(CreateConcreteProductsComponent, {
        ngModule: {
            imports: [ScrollingModule, InvokeModule],
            declarations: [ConcreteProductAttributesSelectorComponent, ConcreteProductsPreviewComponent],
            schemas: [NO_ERRORS_SCHEMA],
        },
        projectContent: `
            <span preview-text></span>
            <span preview-total-text></span>
            <span preview-auto-sku-text></span>
            <span preview-auto-name-text></span>
            <span preview-col-attr-name></span>
            <span preview-col-sku-name></span>
            <span preview-col-name-name></span>
            <span preview-no-data-text></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
            providers: [ConcreteProductGeneratorDataService],
        });
    });

    describe('Slots and Components', () => {
        it('should render <mp-concrete-product-attributes-selector> component', async () => {
            const host = await createComponentWrapper(createComponent, { attributes: [], selectedAttributes: [] });
            const concreteProductAttributesSelectorComponent = host.queryCss('mp-concrete-product-attributes-selector');

            expect(concreteProductAttributesSelectorComponent).toBeTruthy();
        });

        it('should render `preview-text` slot to the `.mp-create-concrete-products__preview-title` element', async () => {
            const host = await createComponentWrapper(createComponent, { attributes: [], selectedAttributes: [] });
            const previewTextSlot = host.queryCss('.mp-create-concrete-products__preview-title [preview-text]');

            expect(previewTextSlot).toBeTruthy();
        });

        it('should render <mp-concrete-products-preview> component', async () => {
            const host = await createComponentWrapper(createComponent, { attributes: [], selectedAttributes: [] });
            const concreteProductsPreviewComponent = host.queryCss('mp-concrete-products-preview');

            expect(concreteProductsPreviewComponent).toBeTruthy();
        });

        describe('<mp-concrete-products-preview> component', () => {
            it('should render `preview-total-text` slot', async () => {
                const host = await createComponentWrapper(createComponent, {
                    attributes: mockAttributes,
                    selectedAttributes: mockUpdatedSelectedAttributes,
                });
                const previewTotalTextSlot = host.queryCss('mp-concrete-products-preview [preview-total-text]');

                expect(previewTotalTextSlot).toBeTruthy();
            });

            it('should render `preview-auto-sku-text` slot', async () => {
                const host = await createComponentWrapper(createComponent, {
                    attributes: mockAttributes,
                    selectedAttributes: mockUpdatedSelectedAttributes,
                });
                const previewAutoSkuTextSlot = host.queryCss('mp-concrete-products-preview [preview-auto-sku-text]');

                expect(previewAutoSkuTextSlot).toBeTruthy();
            });

            it('should render `preview-auto-name-text` slot', async () => {
                const host = await createComponentWrapper(createComponent, {
                    attributes: mockAttributes,
                    selectedAttributes: mockUpdatedSelectedAttributes,
                });
                const previewAutoNameTextSlot = host.queryCss('mp-concrete-products-preview [preview-auto-name-text]');

                expect(previewAutoNameTextSlot).toBeTruthy();
            });

            it('should render `preview-col-attr-name` slot', async () => {
                const host = await createComponentWrapper(createComponent, {
                    attributes: mockAttributes,
                    selectedAttributes: mockUpdatedSelectedAttributes,
                });
                const previewColAttrNameSlot = host.queryCss('mp-concrete-products-preview [preview-col-attr-name]');

                expect(previewColAttrNameSlot).toBeTruthy();
            });

            it('should render `preview-col-sku-name` slot', async () => {
                const host = await createComponentWrapper(createComponent, {
                    attributes: mockAttributes,
                    selectedAttributes: mockUpdatedSelectedAttributes,
                });
                const previewColSkuNameSlot = host.queryCss('mp-concrete-products-preview [preview-col-sku-name]');

                expect(previewColSkuNameSlot).toBeTruthy();
            });

            it('should render `preview-col-name-name` slot', async () => {
                const host = await createComponentWrapper(createComponent, {
                    attributes: mockAttributes,
                    selectedAttributes: mockUpdatedSelectedAttributes,
                });
                const previewColNameNameSlot = host.queryCss('mp-concrete-products-preview [preview-col-name-name]');

                expect(previewColNameNameSlot).toBeTruthy();
            });

            it('should render `preview-no-data-text` slot', async () => {
                const host = await createComponentWrapper(createComponent, { attributes: [], selectedAttributes: [] });
                const previewNoDataTextSlot = host.queryCss('mp-concrete-products-preview [preview-no-data-text]');

                expect(previewNoDataTextSlot).toBeTruthy();
            });
        });
    });

    describe('@Inputs', () => {
        it('should bound `@Input(attributes)` to the `attributes` input of <mp-concrete-product-attributes-selector> component', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
            });
            const concreteProductAttributesSelectorComponent = host.queryCss('mp-concrete-product-attributes-selector');

            expect(concreteProductAttributesSelectorComponent.componentInstance.attributes).toBe(mockAttributes);
        });

        it('should bound `@Input(attributeErrors)` to the `errors` input of <mp-concrete-product-attributes-selector> component', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributeErrors: mockAttributeErrors,
                attributes: [],
                selectedAttributes: [],
            });
            const concreteProductAttributesSelectorComponent = host.queryCss('mp-concrete-product-attributes-selector');

            expect(concreteProductAttributesSelectorComponent.componentInstance.errors).toBe(mockAttributeErrors);
        });

        it('should bound `@Input(selectedAttributes)` to the `selectedAttributes` input of <mp-concrete-product-attributes-selector> component and to the `attributes` input of <mp-concrete-products-preview> component', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: mockSelectedAttributes,
            });
            const concreteProductAttributesSelectorComponent = host.queryCss('mp-concrete-product-attributes-selector');
            const concreteProductsPreviewComponent = host.queryCss('mp-concrete-products-preview');

            expect(concreteProductAttributesSelectorComponent.componentInstance.selectedAttributes[0]).toStrictEqual(
                mockSelectedAttributes[0],
            );
            expect(concreteProductsPreviewComponent.componentInstance.attributes[0]).toStrictEqual(
                mockSelectedAttributes[0],
            );
        });

        it('should bound `@Input(existingProducts)` to the `existingProducts` input of <mp-concrete-products-preview> component', async () => {
            const host = await createComponentWrapper(createComponent, {
                existingProducts: mockExistingProducts,
                attributes: [],
                selectedAttributes: [],
            });
            const concreteProductsPreviewComponent = host.queryCss('mp-concrete-products-preview');

            expect(concreteProductsPreviewComponent.componentInstance.existingProducts).toBe(mockExistingProducts);
        });

        it('should bound `@Input(generatedProducts)` to the `generatedProducts` input of <mp-concrete-products-preview> component', async () => {
            const host = await createComponentWrapper(createComponent, {
                generatedProducts: mockGeneratedProducts,
                attributes: [],
                selectedAttributes: [],
            });
            const concreteProductsPreviewComponent = host.queryCss('mp-concrete-products-preview');

            expect(concreteProductsPreviewComponent.componentInstance.generatedProducts).toStrictEqual(
                mockGeneratedProducts,
            );
        });

        it('should bound `@Input(generatedProductErrors)` to the `errors` input of <mp-concrete-products-preview> component', async () => {
            const host = await createComponentWrapper(createComponent, {
                generatedProductErrors: mockGeneratedProductErrors,
                attributes: [],
                selectedAttributes: [],
            });
            const concreteProductsPreviewComponent = host.queryCss('mp-concrete-products-preview');

            expect(concreteProductsPreviewComponent.componentInstance.errors).toBe(mockGeneratedProductErrors);
        });

        it('should bound `@Input(productsName)` to the `name` input of <mp-concrete-products-preview> component', async () => {
            const host = await createComponentWrapper(createComponent, {
                productsName: mockProductsName,
                attributes: [],
                selectedAttributes: [],
            });
            const concreteProductsPreviewComponent = host.queryCss('mp-concrete-products-preview');

            expect(concreteProductsPreviewComponent.componentInstance.name).toBe(mockProductsName);
        });

        it('should bound `@Input(attributesName)` to the `name` input of <mp-concrete-product-attributes-selector> component', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributesName: mockAttributesName,
                attributes: [],
                selectedAttributes: [],
            });
            const concreteProductAttributesSelectorComponent = host.queryCss('mp-concrete-product-attributes-selector');

            expect(concreteProductAttributesSelectorComponent.componentInstance.name).toBe(mockAttributesName);
        });

        it('should bound `@Input(attributesPlaceholder)` to the `placeholder` input of <mp-concrete-product-attributes-selector> component', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributesPlaceholder: mockAttributesPlaceholder,
                attributes: [],
                selectedAttributes: [],
            });
            const concreteProductAttributesSelectorComponent = host.queryCss('mp-concrete-product-attributes-selector');

            expect(concreteProductAttributesSelectorComponent.componentInstance.placeholder).toBe(
                mockAttributesPlaceholder,
            );
        });

        it('should update <mp-concrete-products-preview> component `attributes` input when `selectedAttributesChange` event emitted', async () => {
            const host = await createComponentWrapper(createComponent, {
                attributes: mockAttributes,
                selectedAttributes: [],
            });
            const concreteProductAttributesSelectorComponent = host.queryCss('mp-concrete-product-attributes-selector');
            const concreteProductsPreviewComponent = host.queryCss('mp-concrete-products-preview');

            concreteProductAttributesSelectorComponent.triggerEventHandler(
                'selectedAttributesChange',
                mockSelectedAttributes,
            );
            host.detectChanges();

            expect(concreteProductsPreviewComponent.componentInstance.attributes).toBe(mockSelectedAttributes);
        });
    });
});
