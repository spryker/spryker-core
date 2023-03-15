import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { ProfileComponent } from './profile.component';

describe('ProfileComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(ProfileComponent, {
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

    describe('Profile header', () => {
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

    describe('Profile content', () => {
        it('should render `.mp-profile__col--content` element', async () => {
            const host = await createComponentWrapper(createComponent);
            const contentElem = host.queryCss('.mp-profile__col--content');

            expect(contentElem).toBeTruthy();
        });

        it('should render default slot to the `.mp-profile__col--content` element', async () => {
            const host = await createComponentWrapper(createComponent);
            const defaultSlot = host.queryCss('.mp-profile__col--content .default-slot');

            expect(defaultSlot).toBeTruthy();
        });
    });
});
