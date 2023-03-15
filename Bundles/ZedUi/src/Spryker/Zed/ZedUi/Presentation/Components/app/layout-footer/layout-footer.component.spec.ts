import { TestBed } from '@angular/core/testing';
import { NO_ERRORS_SCHEMA } from '@angular/core';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { LayoutFooterComponent } from './layout-footer.component';

describe('LayoutFooterComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(LayoutFooterComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `<span class="default-slot"></span>`,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render default slot to the `.mp-layout-footer__content` element', async () => {
        const host = await createComponentWrapper(createComponent);
        const defaultSlot = host.queryCss('.mp-layout-footer__content .default-slot');

        expect(defaultSlot).toBeTruthy();
    });
});
