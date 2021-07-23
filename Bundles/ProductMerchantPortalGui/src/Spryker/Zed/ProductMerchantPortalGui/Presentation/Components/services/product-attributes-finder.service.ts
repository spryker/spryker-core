import { Injectable } from '@angular/core';
import { AttributeCollection, ConcreteProductPreview, ConcreteProductPreviewSuperAttribute } from './types';

@Injectable()
export class ProductAttributesFinderService {
    private getAttributesHash(superAttributes: ConcreteProductPreviewSuperAttribute[]): string {
        return superAttributes.reduce(
            (attributesHash, attribute) => attributesHash + attribute.value + attribute.attribute.value,
            '',
        );
    }

    getAttributeCollection(existingProducts: ConcreteProductPreview[]): AttributeCollection {
        return existingProducts.reduce((attributeCollection, product) => {
            const hash = this.getAttributesHash(product.superAttributes);
            attributeCollection[hash] = true;

            return attributeCollection;
        }, {} as AttributeCollection);
    }

    isAttributeNew(
        attribute: ConcreteProductPreviewSuperAttribute[],
        existingAttributes: AttributeCollection,
    ): boolean {
        return !(this.getAttributesHash(attribute) in existingAttributes);
    }
}
