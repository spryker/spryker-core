import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import {
    ConcreteProductNameGeneratorProviderToken,
    ConcreteProductNameGeneratorToken,
    ConcreteProductSkuGeneratorProviderToken,
    ConcreteProductSkuGeneratorToken,
} from '../../services/tokens';
import { ConcreteProductGeneratorData } from '../../services/types';
import { ConcreteProductGeneratorDataService } from '../../services/concrete-product-generator-data.service';
import { ConcreteProductSkuGeneratorService } from '../../services/concrete-product-sku-generator.service';
import { ConcreteProductNameGeneratorService } from '../../services/concrete-product-name-generator.service';

@Component({
    selector: 'mp-concrete-product-generator-data',
    templateUrl: './concrete-product-generator-data.component.html',
    styleUrls: ['./concrete-product-generator-data.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    providers: [
        {
            provide: ConcreteProductGeneratorDataService,
            useExisting: ConcreteProductGeneratorDataComponent,
        },
        {
            provide: ConcreteProductSkuGeneratorProviderToken,
            useValue: {
                provide: ConcreteProductSkuGeneratorToken,
                useClass: ConcreteProductSkuGeneratorService,
            },
        },
        {
            provide: ConcreteProductNameGeneratorProviderToken,
            useValue: {
                provide: ConcreteProductNameGeneratorToken,
                useClass: ConcreteProductNameGeneratorService,
            },
        },
    ],
})
export class ConcreteProductGeneratorDataComponent implements ConcreteProductGeneratorData {
    @Input() abstractSku = '';
    @Input() abstractName = '';

    getAbstractName(): string {
        return this.abstractName;
    }

    getAbstractSku(): string {
        return this.abstractSku;
    }
}
