<?php

namespace Catalog\Service;

class Product extends AbstractService
{
    protected $entityMapper = 'catalog_product_mapper';
    protected $optionService;
    protected $productUomService;
    protected $imageService;
    protected $documentService;
    protected $specService;
    protected $companyService;

    public function find(array $data, $populate=false, $recursive=false)
    {
        $product = $this->getEntityMapper()->find($data);
        if($populate) {
            $this->populate($product, $recursive);
        }
        return $product;
    }

    public function getFullProduct($productId)
    {
        $product = $this->find(array('product_id' => $productId));
        $this->populate($product, true);
        return $product;
    }

    public function populate($product, $recursive=false)
    {
        $productId = $product->getProductId();
        $product->setOptions($this->getOptionService()->getByProductId($productId, true, $recursive));
        $product->setImages($this->getImageService()->getImages('product', $productId));
        $product->setDocuments($this->getDocumentService()->getDocuments($productId));
        $product->setUoms($this->getProductUomService()->getByProductId($productId, true, $recursive));
        $product->setSpecs($this->getSpecService()->getByProductId($productId));
        $product->setManufacturer($this->getCompanyService()->findById($product->getManufacturerId()));
    }

    public function addOption($productOrId, $optionOrId)
    {
        $productId = ( is_int($productOrId) ? $productOrId : $productOrId->getProductId() );
        $optionId  = ( is_int($optionOrId)
            ? $optionOrId
            : $this->getOptionService()->persist($optionOrId)->getOptionId()
        );
        $this->getEntityMapper()->addOption($productId, $optionId);
        return $this->getOptionService()->find(array('option_id' => $optionId));
    }

    public function addProductUom($productOrId, $productUom)
    {
        $productId = ( is_int($productOrId) ? $productOrId : $productOrId->getProductId() );
        $productUom = $this->getProductUomService()->persist($productUom);
        var_dump($productUom); die();
    }

    public function removeOption($productOrId, $optionOrId)
    {
        $productId = ( is_int($productOrId) ? $productOrId : $productOrId->getProductId() );
        $optionId  = ( is_int($optionOrId)  ? $optionOrId  : $optionOrId->getOptionId() );
        $this->getEntityMapper()->removeOption($productId, $optionId);
    }

    public function addProductImage($productOrId, $image)
    {
        $productId = ( is_int($productOrId) ? $productOrId : $productOrId->getProductId() );
        $imageId = $this->getImageService()->persist($image)->getImageId();
        $this->getEntityMapper()->addImage($productId, $imageId);
        return $this->getImageService()->find(array('image_id' => $imageId));
    }

    public function addSpec($productOrId, $spec)
    {
        $productId = ( is_int($productOrId) ? $productOrId : $productOrId->getProductId() );
        $specId = $this->getSpecService()->persist($spec)->getSpecId();
        return $this->getSpecService()->find(array('spec_id' => $specId));
    }

    public function addDocument($productOrId, $document)
    {
        $productId = ( is_int($productOrId) ? $productOrId : $productOrId->getProductId() );
        $documentId = $this->getDocumentService()->persist($document)->getDocumentId();
        return $this->getDocumentService()->find(array('document_id' => $documentId));
    }

    public function populateForPricing($product)
    {
        //recursive options (only whats needed for pricing)
        //productUoms, Availabilities
        //
    }

    public function getByCategoryId($categoryId, $options=array())
    {
        return $this->usePaginator($options)->getEntityMapper()->getByCategoryId($categoryId);
    }

    /**
     * @return optionService
     */
    public function getOptionService()
    {
        if (null === $this->optionService) {
            $this->optionService = $this->getServiceLocator()->get('catalog_option_service');
        }
        return $this->optionService;
    }

    /**
     * @param $optionService
     * @return self
     */
    public function setOptionService($optionService)
    {
        $this->optionService = $optionService;
        return $this;
    }

    /**
     * @return productUomService
     */
    public function getProductUomService()
    {
        if (null === $this->productUomService) {
            $this->productUomService = $this->getServiceLocator()->get('catalog_product_uom_service');
        }
        return $this->productUomService;
    }

    /**
     * @param $productUomService
     * @return self
     */
    public function setProductUomService($productUomService)
    {
        $this->productUomService = $productUomService;
        return $this;
    }

    /**
     * @return imageService
     */
    public function getImageService()
    {
        if (null === $this->imageService) {
            $this->imageService = $this->getServiceLocator()->get('catalog_product_image_service');
        }
        return $this->imageService;
    }

    /**
     * @param $imageService
     * @return self
     */
    public function setImageService($imageService)
    {
        $this->imageService = $imageService;
        return $this;
    }

    /**
     * @return documentService
     */
    public function getDocumentService()
    {
        if (null === $this->documentService) {
            $this->documentService = $this->getServiceLocator()->get('catalog_document_service');
        }
        return $this->documentService;
    }

    /**
     * @param $documentService
     * @return self
     */
    public function setDocumentService($documentService)
    {
        $this->documentService = $documentService;
        return $this;
    }

    /**
     * @return specService
     */
    public function getSpecService()
    {
        if (null === $this->specService) {
            $this->specService = $this->getServiceLocator()->get('catalog_spec_service');
        }
        return $this->specService;
    }

    /**
     * @param $specService
     * @return self
     */
    public function setSpecService($specService)
    {
        $this->specService = $specService;
        return $this;
    }

    /**
     * @return companyService
     */
    public function getCompanyService()
    {
        if (null === $this->companyService) {
            $this->companyService = $this->getServiceLocator()->get('catalog_company_service');
        }
        return $this->companyService;
    }

    /**
     * @param $companyService
     * @return self
     */
    public function setCompanyService($companyService)
    {
        $this->companyService = $companyService;
        return $this;
    }
}
