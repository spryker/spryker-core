import { Injectable, Injector } from '@angular/core';
import { ActionHandler } from '@spryker/actions';
import { ModalRef, ModalService } from '@spryker/modal';
import { Observable, of } from 'rxjs';
import { RefreshModalActionConfig } from './types';

@Injectable({
    providedIn: 'root',
})
export class RefreshModalActionHandlerService implements ActionHandler<unknown, void> {
    constructor(private modalService: ModalService) {}

    handleAction(injector: Injector, config: RefreshModalActionConfig): Observable<void> {
        const modalRef = injector.get<ModalRef<unknown, unknown>>(ModalRef, null, { optional: true });

        if (modalRef) {
            this.updateModalHtml(modalRef, config);
            return of(void 0);
        }

        const openModals = this.modalService.getOpenModals();

        if (!openModals?.length) {
            return of(void 0);
        }

        const targetModal = config.modalId
            ? openModals.find((modal) => modal.id === config.modalId)
            : openModals[openModals.length - 1];

        this.updateModalHtml(targetModal, config);

        return of(void 0);
    }

    private updateModalHtml(modalRef: ModalRef<unknown, unknown>, config: RefreshModalActionConfig): void {
        const htmlContent = config.form || '';

        if (modalRef && typeof modalRef.updateHtml === 'function') {
            modalRef.updateHtml(htmlContent);
        }
    }
}
