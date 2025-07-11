import { Injectable, Injector, TemplateRef, Type } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActionHandler } from '@spryker/actions';
import {
    AnyModal,
    ComponentModal,
    HtmlModalStrategy,
    ModalRef,
    ModalService,
    TemplateModalContext,
} from '@spryker/modal';
import { Observable, of, Subscriber } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

import { OpenModalAction } from './types';

@Injectable({
    providedIn: 'root',
})
export class OpenModalActionHandlerService implements ActionHandler<unknown, ModalRef<unknown, unknown>> {
    handleAction(injector: Injector, config: OpenModalAction): Observable<ModalRef<unknown, unknown>> {
        return new Observable((subscriber) => {
            const modalService = injector.get(ModalService);
            const { component, template, confirm, form, id, url, width } = config;
            const data = form;

            const strategies = [
                {
                    key: !!component,
                    handler: () => modalService.openComponent(component as Type<ComponentModal>, { data }),
                },
                {
                    key: !!template,
                    handler: () =>
                        modalService.openTemplate(template as TemplateRef<TemplateModalContext<AnyModal>>, { data }),
                },
                {
                    key: !!confirm,
                    handler: () => {
                        const confirmOptions = typeof confirm === 'object' ? { ...confirm, data } : { data };
                        return modalService.openConfirm(confirmOptions);
                    },
                },
            ];

            if (!data && url) {
                this.fetchDataAndOpenModal(injector, url, config, strategies, modalService, subscriber);
                return;
            }

            const modalRef = this.openModalWithStrategies(strategies, data, modalService, width);

            if (modalRef && id) {
                modalRef.id = String(id);
            }

            subscriber.next(modalRef);
        });
    }

    private fetchDataAndOpenModal(
        injector: Injector,
        url: string,
        config: OpenModalAction,
        strategies: Array<{ key: boolean; handler: () => ModalRef<unknown, unknown> }>,
        modalService: ModalService,
        subscriber: Subscriber<ModalRef<unknown, unknown>>,
    ): void {
        const httpClient = injector.get(HttpClient);
        const payload = {
            // eslint-disable-next-line camelcase
            ...(config.form_selector ? { form_selector: config.form_selector } : {}),
            // eslint-disable-next-line camelcase
            ...(config.is_login ? { is_login: config.is_login } : {}),
        };

        httpClient
            .post(url, payload)
            .pipe(
                map((response) => this.extractFormContent(response)),
                catchError(() => of(null)),
            )
            .subscribe((formContent) => {
                const data = formContent || config.form;
                const modalRef = this.openModalWithStrategies(strategies, data, modalService, config.width);

                if (modalRef && config.id) {
                    modalRef.id = String(config.id);
                }

                subscriber.next(modalRef);
            });
    }

    private extractFormContent(response: unknown): string | null {
        if (this.hasActions(response)) {
            const action = this.findOpenModalAction(response);
            if (action?.form) {
                return action.form as string;
            }
        }
        return null;
    }

    private hasActions(response: unknown): response is { actions: unknown[] } {
        return !!(response as { actions?: unknown[] })?.actions?.length;
    }

    private findOpenModalAction(response: { actions: unknown[] }): OpenModalAction | null {
        return (
            (response.actions.find(
                (action: unknown) => (action as { type?: string }).type === 'open-modal',
            ) as OpenModalAction) || null
        );
    }

    private openModalWithStrategies(
        strategies: Array<{ key: boolean; handler: () => ModalRef<unknown, unknown> }>,
        data: unknown,
        modalService: ModalService,
        width: string = '700px',
    ): ModalRef<unknown, unknown> | null {
        for (const { key, handler } of strategies) {
            if (key) {
                return handler();
            }
        }

        if (!data) {
            return null;
        }

        return modalService.open(
            new HtmlModalStrategy({
                html: () => `${data}`,
            }),
            {
                width,
            },
        );
    }
}
