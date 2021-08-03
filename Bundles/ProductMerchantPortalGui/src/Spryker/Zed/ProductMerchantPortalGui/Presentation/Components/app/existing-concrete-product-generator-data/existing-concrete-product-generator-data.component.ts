import { ChangeDetectionStrategy, Component, Input, ViewEncapsulation } from '@angular/core';
import { ToJson } from '@spryker/utils';
import {
    ConcreteProductNameGeneratorProviderToken,
    ConcreteProductNameGeneratorToken,
    ConcreteProductSkuGeneratorProviderToken,
    ConcreteProductSkuGeneratorToken,
} from '../../services/tokens';
import { ConcreteProductGeneratorDataService } from '../../services/concrete-product-generator-data.service';
import { ExistingConcreteProductGeneratorDataService } from '../../services/existing-concrete-product-generator-data.service';
import { ExistingConcreteProductSkuGeneratorService } from '../../services/existing-concrete-product-sku-generator.service';
import { ConcreteProductNameGeneratorService } from '../../services/concrete-product-name-generator.service';
import { ExistingConcreteProductGeneratorData } from '../../services/types';
import { ConcreteProductPreview } from '../../services/types';

@Component({
    selector: 'mp-existing-concrete-product-generator-data',
    templateUrl: './existing-concrete-product-generator-data.component.html',
    styleUrls: ['./existing-concrete-product-generator-data.component.less'],
    changeDetection: ChangeDetectionStrategy.OnPush,
    encapsulation: ViewEncapsulation.None,
    providers: [
        {
            provide: ConcreteProductGeneratorDataService,
            useExisting: ExistingConcreteProductGeneratorDataComponent,
        },
        {
            provide: ExistingConcreteProductGeneratorDataService,
            useExisting: ExistingConcreteProductGeneratorDataComponent,
        },
        {
            provide: ConcreteProductSkuGeneratorProviderToken,
            useValue: {
                provide: ConcreteProductSkuGeneratorToken,
                useClass: ExistingConcreteProductSkuGeneratorService,
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
export class ExistingConcreteProductGeneratorDataComponent implements ExistingConcreteProductGeneratorData {
    @Input() abstractSku = '';
    @Input() abstractName = '';
    @Input() @ToJson() existingProducts?: ConcreteProductPreview[];

    getAbstractName(): string {
        return this.abstractName;
    }

    getAbstractSku(): string {
        return this.abstractSku;
    }

    getExistingProducts(): ConcreteProductPreview[] {
        return this.existingProducts;
    }
}
