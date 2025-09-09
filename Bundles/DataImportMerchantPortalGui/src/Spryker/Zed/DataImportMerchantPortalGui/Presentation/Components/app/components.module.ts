import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';
import { ButtonActionComponent, ButtonActionModule } from '@spryker/button.action';
import { CardComponent, CardModule } from '@spryker/card';
import { IconModule } from '@spryker/icon';

import { IconDataImportModule, IconDownloadModule, IconUploadModule } from '../icons';
import { DataImportsComponent } from './data-imports/data-imports.component';
import { DataImportsModule } from './data-imports/data-imports.module';
import { DataImportComponent } from './data-import/data-import.component';
import { DataImportModule } from './data-import/data-import.module';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            DataImportsComponent,
            DataImportComponent,
            ButtonActionComponent,
            CardComponent,
        ]),
        ButtonActionModule,
        DataImportsModule,
        DataImportModule,
        CardModule,
        IconModule,
        IconDataImportModule,
        IconDownloadModule,
        IconUploadModule,
    ],
})
export class ComponentsModule {}
