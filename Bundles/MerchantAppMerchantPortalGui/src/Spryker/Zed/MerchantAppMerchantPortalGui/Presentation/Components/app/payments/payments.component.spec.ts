import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { PaymentsComponent } from './payments.component';

describe('PaymentsComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(PaymentsComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `
            <span title></span>
            <span action></span>
            <span class="default-slot"></span>
        `,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    describe('Payments header', () => {
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

        it('should render `action` slot to the <spy-headline> component', async () => {
            const host = await createComponentWrapper(createComponent);
            const actionSlot = host.queryCss('spy-headline [action]');

            expect(actionSlot).toBeTruthy();
        });
    });

    describe('Payments content', () => {
        it('should render default slot after spy-headline component', async () => {
            const host = await createComponentWrapper(createComponent);
            const defaultSlot = host.queryCss('spy-headline + .default-slot');

            expect(defaultSlot).toBeTruthy();
        });
    });
});
