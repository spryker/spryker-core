import { NgModule } from '@angular/core';
import { CommentsThreadComponent, CommentsThreadModule } from '@mp/comment-merchant-portal-gui';
import { ButtonComponent, ButtonLinkComponent, ButtonModule, ButtonLinkModule } from '@spryker/button';
import { CardComponent, CardModule } from '@spryker/card';
import { WebComponentsModule } from '@spryker/web-components';
import { EditMerchantRelationshipComponent } from './edit-merchant-relationship/edit-merchant-relationship.component';
import { EditMerchantRelationshipModule } from './edit-merchant-relationship/edit-merchant-relationship.module';
import { MerchantAddressListComponent } from './merchant-address-list/merchant-address-list.component';
import { MerchantAddressListModule } from './merchant-address-list/merchant-address-list.module';
import { MerchantRelationshipComponent } from './merchant-relationship/merchant-relationship.component';
import { MerchantRelationshipModule } from './merchant-relationship/merchant-relationship.module';

@NgModule({
    imports: [
        WebComponentsModule.withComponents([
            MerchantRelationshipComponent,
            EditMerchantRelationshipComponent,
            MerchantAddressListComponent,
            CardComponent,
            CommentsThreadComponent,
            ButtonComponent,
            ButtonLinkComponent,
        ]),
        MerchantRelationshipModule,
        EditMerchantRelationshipModule,
        MerchantAddressListModule,
        CardModule,
        CommentsThreadModule,
        ButtonModule,
        ButtonLinkModule,
    ],
})
export class ComponentsModule {}
