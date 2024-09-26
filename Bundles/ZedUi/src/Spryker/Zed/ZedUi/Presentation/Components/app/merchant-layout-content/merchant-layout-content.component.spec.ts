import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { MerchantLayoutContentComponent } from './merchant-layout-content.component';

describe('MerchantLayoutContentComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(MerchantLayoutContentComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span title></span>
            <span button-action></span>
            <span main></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render main content next to <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const content = host.queryCss('spy-headline + [main]');

        expect(content).toBeTruthy();
    });

    it('should render <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const headlineComponent = host.queryCss('spy-headline');

        expect(headlineComponent).toBeTruthy();
    });

    it('should render `title` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const titleSlot = host.queryCss('spy-headline [title]');

        expect(titleSlot).toBeTruthy();
    });

    it('should render `button-action` slot to the <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const buttonActionSlot = host.queryCss('spy-headline [button-action]');

        expect(buttonActionSlot).toBeTruthy();
    });
});
