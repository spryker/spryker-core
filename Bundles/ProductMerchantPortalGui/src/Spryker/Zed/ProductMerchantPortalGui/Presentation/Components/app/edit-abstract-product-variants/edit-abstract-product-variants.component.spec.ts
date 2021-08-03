import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { Component, NO_ERRORS_SCHEMA } from '@angular/core';
import { CardModule } from '@spryker/card';

import { EditAbstractProductVariantsComponent } from './edit-abstract-product-variants.component';

@Component({
    selector: 'test',
    template: `
        <mp-edit-abstract-product-variants [config]="config" [tableId]="tableId">
            <div class="default-content"></div>
            <div title class="title-content"></div>
        </mp-edit-abstract-product-variants>
    `,
})
class TestComponent {
    config: any;
    tableId: string;
}

describe('EditAbstractProductVariantsComponent', () => {
    let component: TestComponent;
    let fixture: ComponentFixture<TestComponent>;

    describe('Host functionality', () => {
        beforeEach(async(() => {
            TestBed.configureTestingModule({
                declarations: [EditAbstractProductVariantsComponent, TestComponent],
                schemas: [NO_ERRORS_SCHEMA],
            }).compileComponents();
        }));

        beforeEach(() => {
            fixture = TestBed.createComponent(TestComponent);
            component = fixture.componentInstance;
        });

        it('should create component', () => {
            expect(component).toBeTruthy();
        });

        it('should render `spy-card` component', () => {
            const cardComponent = fixture.debugElement.query(By.css('spy-card'));

            expect(cardComponent).toBeTruthy();
        });

        it('should render `spy-table` component into `spy-card` component', () => {
            const tableComponent = fixture.debugElement.query(By.css('spy-card spy-table'));

            expect(tableComponent).toBeTruthy();
        });

        it('should bind @Input(config) to `config` of `spy-table` component', () => {
            const mockTableConfig = {
                config: 'config',
                data: 'data',
                columns: 'columns',
            } as any;
            const tableComponent = fixture.debugElement.query(By.css('spy-table'));

            component.config = mockTableConfig;
            fixture.detectChanges();

            expect(tableComponent.properties.config).toEqual(mockTableConfig);
        });

        it('should bind @Input(tableId) to `tableId` of `spy-table` component', () => {
            const mockTableId = 'mockTableId';
            const tableComponent = fixture.debugElement.query(By.css('spy-table'));

            component.tableId = mockTableId;
            fixture.detectChanges();

            expect(tableComponent.properties.tableId).toEqual(mockTableId);
        });
    });

    describe('Card element', () => {
        beforeEach(async(() => {
            TestBed.configureTestingModule({
                imports: [CardModule],
                declarations: [EditAbstractProductVariantsComponent, TestComponent],
                schemas: [NO_ERRORS_SCHEMA],
            }).compileComponents();
        }));

        beforeEach(() => {
            fixture = TestBed.createComponent(TestComponent);
            component = fixture.componentInstance;
        });

        it('should render default content in the `.ant-card-extra` element', () => {
            fixture.detectChanges();

            const titleContentElement = fixture.debugElement.query(By.css('.ant-card-extra .default-content'));

            expect(titleContentElement).toBeTruthy();
        });

        it('should render title content in the `.ant-card-head-title` element', () => {
            fixture.detectChanges();

            const titleContentElement = fixture.debugElement.query(By.css('.ant-card-head-title .title-content'));

            expect(titleContentElement).toBeTruthy();
        });
    });
});
