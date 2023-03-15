import { NO_ERRORS_SCHEMA } from '@angular/core';
import { TestBed } from '@angular/core/testing';
import { createComponentWrapper, getTestingForComponent } from '@mp/zed-ui/testing';
import { HeaderComponent } from './header.component';

describe('HeaderComponent', () => {
    const { testModule, createComponent } = getTestingForComponent(HeaderComponent, {
        ngModule: { schemas: [NO_ERRORS_SCHEMA] },
        projectContent: `<span menu></span>`,
    });

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports: [testModule],
        });
    });

    it('should render `menu` slot to the host element', async () => {
        const host = await createComponentWrapper(createComponent);
        const menuSlot = host.queryCss('[menu]');

        expect(menuSlot).toBeTruthy();
    });
});
