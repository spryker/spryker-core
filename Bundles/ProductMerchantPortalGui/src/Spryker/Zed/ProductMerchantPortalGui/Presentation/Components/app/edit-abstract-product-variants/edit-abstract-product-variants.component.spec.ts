import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { CardModule } from '@spryker/card';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { EditAbstractProductVariantsComponent } from './edit-abstract-product-variants.component';

describe('EditAbstractProductVariantsComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(EditAbstractProductVariantsComponent, {
        ngModule: {
            imports: [CardModule],
            schemas: [NO_ERRORS_SCHEMA],
        },
        projectContent: `
            <span title></span>
            <span class="default-slot"></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render <spy-card> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const cardComponent = host.queryCss('spy-card');

        expect(cardComponent).toBeTruthy();
    });

    it('should render `title` slot to the `.ant-card-head-title` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const titleSlot = host.queryCss('.ant-card-head-title [title]');

        expect(titleSlot).toBeTruthy();
    });

    it('should render default slot to the `.ant-card-extra` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.ant-card-extra .default-slot');

        expect(defaultSlot).toBeTruthy();
    });

    it('should render <spy-table> component to the <spy-card> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const tableComponent = host.queryCss('spy-card spy-table');

        expect(tableComponent).toBeTruthy();
    });

    it('should bound `@Input(config)` to the `config` input of <spy-table> component', async () => {
        const mockTableConfig = {
            config: 'config',
            data: 'data',
            columns: 'columns',
        };
        const host = await createComponentWrapper(createComponent, { config: mockTableConfig });
        const tableComponent = host.queryCss('spy-table');

        expect(tableComponent.properties.config).toEqual(mockTableConfig);
    });

    it('should bound `@Input(tableId)` to the `tableId` input of <spy-table> component', async () => {
        const mockTableId = 'mockTableId';
        const host = await createComponentWrapper(createComponent, { tableId: mockTableId });
        const tableComponent = host.queryCss('spy-table');

        expect(tableComponent.properties.tableId).toEqual(mockTableId);
    });
});
