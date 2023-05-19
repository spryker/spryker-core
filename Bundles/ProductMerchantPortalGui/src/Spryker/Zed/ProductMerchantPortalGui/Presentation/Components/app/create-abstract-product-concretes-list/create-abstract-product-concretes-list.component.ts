import { ChangeDetectionStrategy, Component, Input, OnInit, ViewEncapsulation } from '@angular/core';
import { IconInfoModule } from '@spryker/icon/icons';
import { ToJson } from '@spryker/utils';
import { ConcretesListForm } from './types';

@Component({
    selector: 'mp-create-abstract-product-concretes-list',
    templateUrl: './create-abstract-product-concretes-list.component.html',
    styleUrls: ['./create-abstract-product-concretes-list.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    host: {
        class: 'mp-create-abstract-product-concretes-list',
    },
})
export class CreateAbstractProductConcretesListComponent implements OnInit {
    @Input() @ToJson() form: ConcretesListForm;

    hasNotificationMessage = false;
    infoIcon = IconInfoModule.icon;

    ngOnInit(): void {
        this.toggleMessage(this.form.value);
    }

    onChange(value: string): void {
        this.toggleMessage(value);
    }

    private toggleMessage(value: string): void {
        if (!value) {
            return;
        }

        this.hasNotificationMessage = this.form.choices.some(
            (item) => item.value === value && item.hasNotificationMessage,
        );
    }
}
