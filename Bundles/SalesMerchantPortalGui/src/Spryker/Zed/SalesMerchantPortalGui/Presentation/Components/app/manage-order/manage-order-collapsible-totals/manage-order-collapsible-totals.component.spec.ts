import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { NO_ERRORS_SCHEMA } from '@angular/core';

import { ManageOrderCollapsibleTotalsComponent } from './manage-order-collapsible-totals.component';

describe('ManageOrderCollapsibleTotalsComponent', () => {
    let component: ManageOrderCollapsibleTotalsComponent;
    let fixture: ComponentFixture<ManageOrderCollapsibleTotalsComponent>;

    beforeEach(async(() => {
        TestBed.configureTestingModule({
            declarations: [ManageOrderCollapsibleTotalsComponent],
            schemas: [NO_ERRORS_SCHEMA],
        }).compileComponents();
    }));

    beforeEach(() => {
        fixture = TestBed.createComponent(ManageOrderCollapsibleTotalsComponent);
        component = fixture.componentInstance;
    });

    it('should render `spy-html-renderer` component', () => {
        const htmlRendererElem = fixture.debugElement.query(By.css('spy-html-renderer'));

        expect(htmlRendererElem).toBeTruthy();
    });

    it('should render `spy-collapsible` component', () => {
        const collapsobleElem = fixture.debugElement.query(By.css('spy-collapsible'));

        expect(collapsobleElem).toBeTruthy();
    });

    it('should bind @Input(url) to the `urlHtml` input of the `spy-html-renderer` component only when `activeChange` event handled on the collapsible component', () => {
        const collapsobleElem = fixture.debugElement.query(By.css('spy-collapsible'));
        const htmlRendererElem = fixture.debugElement.query(By.css('spy-html-renderer'));
        const mockUrl = 'url';

        component.url = mockUrl;
        fixture.detectChanges();

        expect(htmlRendererElem.properties.urlHtml).toBe(undefined);

        collapsobleElem.triggerEventHandler('activeChange', true);
        fixture.detectChanges();

        expect(htmlRendererElem.properties.urlHtml).toBe(mockUrl);
    });
});
