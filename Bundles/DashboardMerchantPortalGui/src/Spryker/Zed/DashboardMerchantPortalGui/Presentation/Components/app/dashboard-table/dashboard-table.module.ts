import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { TableModule } from '@spryker/table';
import { DashboardTableComponent } from './dashboard-table.component';

@NgModule({
    imports: [CommonModule, TableModule],
    declarations: [DashboardTableComponent],
    exports: [DashboardTableComponent],
})
export class DashboardTableModule {}
