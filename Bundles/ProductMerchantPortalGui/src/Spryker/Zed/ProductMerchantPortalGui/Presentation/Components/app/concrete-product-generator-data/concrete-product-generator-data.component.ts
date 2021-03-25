import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';

@Component({
    selector: 'mp-concrete-product-generator-data',
    templateUrl: './concrete-product-generator-data.component.html',
    styleUrls: ['./concrete-product-generator-data.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
})
export class ConcreteProductGeneratorDataComponent {
    @Input() abstractSku: string;
    @Input() abstractName: string;

    getAbstractName(): string {
        return this.abstractName;
    }

    getAbstractSku(): string {
        return this.abstractSku;
    }
}
