import {
    ChangeDetectionStrategy,
    ChangeDetectorRef,
    Component,
    ElementRef,
    Input,
    ViewChild,
    ViewEncapsulation,
} from '@angular/core';
import { ButtonVariant } from '@spryker/button';
import { ModalService } from '@spryker/modal';
import { ToJson } from '@spryker/utils';

interface Action {
    name: string;
    label: string;
    url: string;
    modalTitle: string;
    modalBody: string;
    modalCancelText?: string;
    modalCancelVariant?: string;
    modalConfirmText?: string;
    modalConfirmVariant?: string;
}

@Component({
    selector: 'mp-merchant-relation-edit',
    templateUrl: './merchant-relation-edit.component.html',
    styleUrls: ['./merchant-relation-edit.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-merchant-relation-edit',
    },
})
export class MerchantRelationEditComponent {
    @Input() @ToJson() actions?: Action[] = [];
    @ViewChild('hiddenButton') hiddenButton: ElementRef<HTMLButtonElement>;

    openPopupName: string;
    actinName: string;

    constructor(private modalService: ModalService, private cdr: ChangeDetectorRef) {}

    openConfirm(action: Action): void {
        this.modalService
            .openConfirm({
                title: action.modalTitle,
                description: action.modalBody,
                cancelText: action.modalCancelText,
                cancelVariant: action.modalCancelVariant as ButtonVariant,
                okText: action.modalConfirmText,
                okVariant: action.modalConfirmVariant as ButtonVariant,
                backdrop: true,
            })
            .afterDismissed()
            .subscribe((isSure) => {
                if (isSure) {
                    this.actinName = action.name;
                    this.cdr.detectChanges();
                    this.hiddenButton.nativeElement.click();
                }
            });
    }
}
