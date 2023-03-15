import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { DashboardComponent } from './dashboard.component';

describe('DashboardComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(DashboardComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
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

    it('should render default slot after <spy-headline> component', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('spy-headline + .default-slot');

        expect(defaultSlot).toBeTruthy();
    });
});
