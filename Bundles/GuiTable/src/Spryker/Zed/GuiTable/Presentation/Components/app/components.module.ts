import { NgModule } from '@angular/core';
import { WebComponentsModule } from '@spryker/web-components';

import { TableComponent } from './table/table.component';
import { TableModule } from './table/table.module';

@NgModule({
    imports: [WebComponentsModule.withComponents([TableComponent]), TableModule],
})
export class ComponentsModule {}
