import { Injectable, Injector } from '@angular/core';
import { ActionHandler } from '@spryker/actions';
import { ModalRef, ModalService } from '@spryker/modal';
import { Observable, of } from 'rxjs';

import { CloseModalActionConfig } from './types';

@Injectable({
    providedIn: 'root',
})
export class CloseModalActionHandlerService implements ActionHandler<unknown, void> {
    constructor(private modalService: ModalService) {}

    handleAction(injector: Injector, config: CloseModalActionConfig): Observable<void> {
        const modalRef = injector.get<ModalRef<unknown, unknown>>(ModalRef, null, { optional: true });

        if (modalRef) {
            return this.closeModalAndReturn(modalRef);
        }

        const openModals = this.modalService.getOpenModals();

        if (!openModals?.length) {
            return of(void 0);
        }

        const targetModal = config.modalId
            ? openModals.find((modal) => modal.id === config.modalId)
            : openModals[openModals.length - 1];

        return this.closeModalAndReturn(targetModal);
    }

    private closeModalAndReturn(modalRef?: ModalRef<unknown, unknown>): Observable<void> {
        if (typeof modalRef?.close === 'function') {
            modalRef.close();
        }
        return of(void 0);
    }
}
